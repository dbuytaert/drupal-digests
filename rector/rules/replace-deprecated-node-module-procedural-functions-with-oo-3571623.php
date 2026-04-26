<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571623
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites three Node module procedural functions deprecated in
// drupal:11.3.0 and removed in drupal:13.0.0 (issue #3571623):
// node_type_get_names() becomes
// \Drupal::service('entity_type.bundle.info')->getBundleLabels('node'),
// node_get_type_label($node) becomes $node->getBundleEntity()->label(),
// and node_mass_update(...) becomes
// \Drupal::service(NodeBulkUpdate::class)->process(...). All arguments
// are preserved.
//
// Before:
//   use Drupal\node\NodeInterface;
//   
//   function example(NodeInterface $node, array $nids, array $updates) {
//     $names = node_type_get_names();
//     $label = node_get_type_label($node);
//     node_mass_update($nids, $updates, 'en', TRUE, FALSE);
//   }
//
// After:
//   use Drupal\node\NodeInterface;
//   
//   function example(NodeInterface $node, array $nids, array $updates) {
//     $names = \Drupal::service('entity_type.bundle.info')->getBundleLabels('node');
//     $label = $node->getBundleEntity()->label();
//     \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process($nids, $updates, 'en', TRUE, FALSE);
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated Node module procedural functions with their successors.
 *
 * Three procedural functions in node.module were deprecated in drupal:11.3.0
 * and are removed in drupal:13.0.0 (issue #3571623):
 *
 *   node_type_get_names()
 *     => \Drupal::service('entity_type.bundle.info')->getBundleLabels('node')
 *
 *   node_get_type_label($node)
 *     => $node->getBundleEntity()->label()
 *
 *   node_mass_update($nodes, $updates, $langcode, $load, $revisions)
 *     => \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process(...)
 *
 * node_is_page() has no replacement and cannot be automatically migrated.
 *
 * @see https://www.drupal.org/node/3534849
 * @see https://www.drupal.org/node/3533301
 * @see https://www.drupal.org/node/3533315
 * @see https://www.drupal.org/project/drupal/issues/3571623
 */
final class ReplaceDeprecatedNodeFunctionsRector extends AbstractRector
{
    private const ENTITY_BUNDLE_INFO_SERVICE = 'entity_type.bundle.info';
    private const NODE_BULK_UPDATE_CLASS = 'Drupal\\node\\NodeBulkUpdate';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Node module procedural functions with their drupal:13.0.0-compatible equivalents',
            [
                new CodeSample(
                    'node_type_get_names();',
                    "\\Drupal::service('entity_type.bundle.info')->getBundleLabels('node');"
                ),
                new CodeSample(
                    'node_get_type_label($node);',
                    '$node->getBundleEntity()->label();'
                ),
                new CodeSample(
                    'node_mass_update($nodes, $updates, $langcode, $load, $revisions);',
                    '\\Drupal::service(\\Drupal\\node\\NodeBulkUpdate::class)->process($nodes, $updates, $langcode, $load, $revisions);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall || !$node->name instanceof Name) {
            return null;
        }

        return match ($node->name->toString()) {
            'node_type_get_names'  => $this->refactorNodeTypeGetNames(),
            'node_get_type_label'  => $this->refactorNodeGetTypeLabel($node),
            'node_mass_update'     => $this->refactorNodeMassUpdate($node),
            default                => null,
        };
    }

    /**
     * node_type_get_names()
     *   => \Drupal::service('entity_type.bundle.info')->getBundleLabels('node')
     */
    private function refactorNodeTypeGetNames(): Node
    {
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_(self::ENTITY_BUNDLE_INFO_SERVICE))]
        );

        return new MethodCall(
            $serviceCall,
            'getBundleLabels',
            [new Arg(new String_('node'))]
        );
    }

    /**
     * node_get_type_label($node)
     *   => $node->getBundleEntity()->label()
     */
    private function refactorNodeGetTypeLabel(FuncCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }

        $nodeArg = $node->args[0]->value;

        $bundleEntityCall = new MethodCall($nodeArg, 'getBundleEntity');

        return new MethodCall($bundleEntityCall, 'label');
    }

    /**
     * node_mass_update($nodes, $updates[, $langcode[, $load[, $revisions]]])
     *   => \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process(...)
     */
    private function refactorNodeMassUpdate(FuncCall $node): ?Node
    {
        if (count($node->args) < 2) {
            return null;
        }

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified(self::NODE_BULK_UPDATE_CLASS),
                'class'
            ))]
        );

        return new MethodCall(
            $serviceCall,
            'process',
            $node->args
        );
    }
}
