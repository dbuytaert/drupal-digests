<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites the two deprecated node access procedural functions,
 * deprecated in drupal:11.4.0 and removed in drupal:13.0.0, to
 * equivalent calls on the \Drupal\node\NodeAccessRebuild service.
 * node_access_rebuild($batch_mode) becomes
 * \Drupal::service(NodeAccessRebuild::class)->rebuild($batch_mode).
 * node_access_needs_rebuild() (no args) becomes ->needsRebuild(), while
 * node_access_needs_rebuild($rebuild) (with an arg) becomes
 * ->setNeedsRebuild($rebuild).
 *
 * Before:
 *   node_access_rebuild();
 *   node_access_rebuild(TRUE);
 *   $needs = node_access_needs_rebuild();
 *   node_access_needs_rebuild(TRUE);
 *   node_access_needs_rebuild(FALSE);
 *
 * After:
 *   \Drupal::service(\Drupal\node\NodeAccessRebuild::class)->rebuild();
 *   \Drupal::service(\Drupal\node\NodeAccessRebuild::class)->rebuild(TRUE);
 *   $needs = \Drupal::service(\Drupal\node\NodeAccessRebuild::class)->needsRebuild();
 *   \Drupal::service(\Drupal\node\NodeAccessRebuild::class)->setNeedsRebuild(TRUE);
 *   \Drupal::service(\Drupal\node\NodeAccessRebuild::class)->setNeedsRebuild(FALSE);
 *
 * @see https://www.drupal.org/node/3533299
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeAccessRebuildFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_access_rebuild() and node_access_needs_rebuild() with the NodeAccessRebuild service.',
            [new CodeSample(
                'node_access_rebuild();',
                '\Drupal::service(\Drupal\node\NodeAccessRebuild::class)->rebuild();',
            )],
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
        $name = $this->getName($node->name);

        if ($name === 'node_access_rebuild') {
            return $this->buildServiceCall('rebuild', $node->args);
        }

        if ($name === 'node_access_needs_rebuild') {
            // 0 args: getter
            if (count($node->args) === 0) {
                return $this->buildServiceCall('needsRebuild', []);
            }
            // 1+ args: setter
            return $this->buildServiceCall('setNeedsRebuild', $node->args);
        }

        return null;
    }

    private function buildServiceCall(string $method, array $args): MethodCall
    {
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            new Identifier('service'),
            [new Arg(new ClassConstFetch(
                new FullyQualified('Drupal\\node\\NodeAccessRebuild'),
                'class',
            ))],
        );

        return new MethodCall(
            $serviceCall,
            new Identifier($method),
            $args,
        );
    }
}
