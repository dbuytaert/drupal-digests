<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3038908
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated procedural function
// node_access_view_all_nodes() with \Drupal::entityTypeManager()-
// >getAccessControlHandler('node')->checkAllGrants(), which was
// introduced in Drupal 11.3.0. Also rewrites
// drupal_static_reset('node_access_view_all_nodes') to the new memory-
// cache service call
// \Drupal::service('node.view_all_nodes_memory_cache')->deleteAll().
// Both forms are removed in Drupal 12.0.0.
//
// Before:
//   node_access_view_all_nodes();
//   node_access_view_all_nodes($account);
//   drupal_static_reset('node_access_view_all_nodes');
//
// After:
//   \Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants(\Drupal::currentUser());
//   \Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants($account);
//   \Drupal::service('node.view_all_nodes_memory_cache')->deleteAll();


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated node_access_view_all_nodes() calls and the matching
 * drupal_static_reset('node_access_view_all_nodes') calls with the OO API
 * introduced in Drupal 11.3.0.
 */
final class NodeAccessViewAllNodesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated node_access_view_all_nodes() with \\Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants()",
            [
                new CodeSample(
                    'node_access_view_all_nodes();',
                    "\\Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants(\\Drupal::currentUser());"
                ),
                new CodeSample(
                    "drupal_static_reset('node_access_view_all_nodes');",
                    "\\Drupal::service('node.view_all_nodes_memory_cache')->deleteAll();"
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
        if ($this->isName($node, 'node_access_view_all_nodes')) {
            return $this->refactorNodeAccessViewAllNodes($node);
        }

        if ($this->isName($node, 'drupal_static_reset')) {
            return $this->refactorDrupalStaticReset($node);
        }

        return null;
    }

    private function refactorNodeAccessViewAllNodes(FuncCall $node): MethodCall
    {
        // Build: \Drupal::entityTypeManager()
        $entityTypeManager = $this->nodeFactory->createStaticCall('Drupal', 'entityTypeManager');

        // Build: ->getAccessControlHandler('node')
        $getHandler = $this->nodeFactory->createMethodCall(
            $entityTypeManager,
            'getAccessControlHandler',
            [new String_('node')]
        );

        // Determine account argument: use passed arg or fall back to \Drupal::currentUser()
        if (!empty($node->args) && $node->args[0] instanceof Arg) {
            $accountArg = $node->args[0]->value;
        } else {
            $accountArg = $this->nodeFactory->createStaticCall('Drupal', 'currentUser');
        }

        // Build: ->checkAllGrants($account)
        return $this->nodeFactory->createMethodCall($getHandler, 'checkAllGrants', [$accountArg]);
    }

    private function refactorDrupalStaticReset(FuncCall $node): ?MethodCall
    {
        if (empty($node->args) || !$node->args[0] instanceof Arg) {
            return null;
        }

        $firstArg = $node->args[0]->value;
        if (!$firstArg instanceof String_ || $firstArg->value !== 'node_access_view_all_nodes') {
            return null;
        }

        // Build: \Drupal::service('node.view_all_nodes_memory_cache')
        $service = $this->nodeFactory->createStaticCall(
            'Drupal',
            'service',
            [new String_('node.view_all_nodes_memory_cache')]
        );

        // Build: ->deleteAll()
        return $this->nodeFactory->createMethodCall($service, 'deleteAll');
    }
}
