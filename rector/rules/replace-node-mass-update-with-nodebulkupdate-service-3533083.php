<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3533083
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated procedural node_mass_update()
// function with the equivalent
// \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process()
// service call. The function is deprecated in drupal:11.3.0 and removed
// in drupal:13.0.0. All arguments map directly to the service method
// with the same signature.
//
// Before:
//   node_mass_update($nodes, $updates, $langcode, $load, $revisions);
//
// After:
//   \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process($nodes, $updates, $langcode, $load, $revisions);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated node_mass_update() calls with the NodeBulkUpdate service.
 */
final class NodeMassUpdateToNodeBulkUpdateRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_mass_update() function calls with \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process()',
            [
                new CodeSample(
                    'node_mass_update($nodes, $updates, $langcode, $load, $revisions);',
                    '\Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process($nodes, $updates, $langcode, $load, $revisions);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'node_mass_update')) {
            return null;
        }

        // Build \Drupal\node\NodeBulkUpdate::class
        $classConst = new ClassConstFetch(
            new FullyQualified('Drupal\node\NodeBulkUpdate'),
            'class'
        );

        // Build \Drupal::service(\Drupal\node\NodeBulkUpdate::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConst)]
        );

        // Build \Drupal::service(\Drupal\node\NodeBulkUpdate::class)->process(...)
        return new MethodCall(
            $serviceCall,
            'process',
            $node->args
        );
    }
}
