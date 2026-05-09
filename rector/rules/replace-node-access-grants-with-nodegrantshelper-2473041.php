<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated global function node_access_grants()
 * with the equivalent call on the new NodeGrantsHelper service: \Drupal:
 * :service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants().
 * The function was deprecated in Drupal 11.4.0 and will be removed in
 * 13.0.0. Any contrib or custom module that calls node_access_grants()
 * to fetch access grants for a user must migrate to the service.
 *
 * Before:
 *   node_access_grants($operation, $account);
 *
 * After:
 *   \Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants($operation, $account);
 *
 * @see https://www.drupal.org/node/2473041
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


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

final class NodeAccessGrantsFuncCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace node_access_grants() with \Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants().',
            [new CodeSample(
                'node_access_grants($operation, $account);',
                '\Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants($operation, $account);',
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
        if (!$node instanceof FuncCall) {
            return null;
        }
        if (!$this->isName($node->name, 'node_access_grants')) {
            return null;
        }
        if (count($node->args) !== 2) {
            return null;
        }
        // \Drupal::service(\Drupal\node\NodeGrantsHelper::class)
        $drupalServiceCall = $this->nodeFactory->createStaticCall('Drupal', 'service', [
            new ClassConstFetch(new FullyQualified('Drupal\\node\\NodeGrantsHelper'), 'class'),
        ]);
        // ->nodeAccessGrants($operation, $account)
        return $this->nodeFactory->createMethodCall($drupalServiceCall, 'nodeAccessGrants', $node->args);
    }
}
