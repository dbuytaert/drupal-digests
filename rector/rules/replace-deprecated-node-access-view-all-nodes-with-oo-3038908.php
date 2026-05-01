<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated node_access_view_all_nodes() function (removed
 * in Drupal 12) with \Drupal::entityTypeManager()-
 * >getAccessControlHandler('node')->checkAllGrants(). Also rewrites
 * drupal_static_reset('node_access_view_all_nodes') to
 * \Drupal::service('node.view_all_nodes_memory_cache')->deleteAll().
 * Both patterns were deprecated in Drupal 11.3.0.
 *
 * Before:
 *   node_access_view_all_nodes($account);
 *   drupal_static_reset('node_access_view_all_nodes');
 *
 * After:
 *   \Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants($account);
 *   \Drupal::service('node.view_all_nodes_memory_cache')->deleteAll();
 *
 * Caveats:
 *   When node_access_view_all_nodes() is called without an argument the
 *   rule passes \Drupal::currentUser() explicitly, matching the
 *   original function's default behaviour.
 *
 * @see https://www.drupal.org/node/3038908
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodeAccessViewAllNodesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_access_view_all_nodes() with the OO equivalent, and replace drupal_static_reset("node_access_view_all_nodes") with the memory cache service call.',
            [
                new CodeSample(
                    'node_access_view_all_nodes($account);',
                    '\Drupal::entityTypeManager()->getAccessControlHandler(\'node\')->checkAllGrants($account);',
                ),
            ],
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

        if ($name === 'node_access_view_all_nodes') {
            // Build: \Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants($account)
            $entityTypeManager = new StaticCall(
                new FullyQualified('Drupal'),
                new Identifier('entityTypeManager'),
                [],
            );
            $handler = new MethodCall(
                $entityTypeManager,
                new Identifier('getAccessControlHandler'),
                [new Arg(new String_('node'))],
            );
            // Use provided $account arg, or fall back to \Drupal::currentUser()
            if (isset($node->args[0]) && $node->args[0] instanceof Arg) {
                $accountArg = $node->args[0];
            } else {
                $currentUser = new StaticCall(
                    new FullyQualified('Drupal'),
                    new Identifier('currentUser'),
                    [],
                );
                $accountArg = new Arg($currentUser);
            }
            return new MethodCall(
                $handler,
                new Identifier('checkAllGrants'),
                [$accountArg],
            );
        }

        if ($name === 'drupal_static_reset') {
            // Only rewrite drupal_static_reset('node_access_view_all_nodes')
            if (!isset($node->args[0]) || !$node->args[0] instanceof Arg) {
                return null;
            }
            $firstArgValue = $node->args[0]->value;
            if (!$firstArgValue instanceof String_ || $firstArgValue->value !== 'node_access_view_all_nodes') {
                return null;
            }
            // Build: \Drupal::service('node.view_all_nodes_memory_cache')->deleteAll()
            $service = new StaticCall(
                new FullyQualified('Drupal'),
                new Identifier('service'),
                [new Arg(new String_('node.view_all_nodes_memory_cache'))],
            );
            return new MethodCall(
                $service,
                new Identifier('deleteAll'),
                [],
            );
        }

        return null;
    }
}
