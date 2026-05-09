<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated node_access_view_all_nodes()
 * procedural function with the equivalent OO call \Drupal::entityTypeMan
 * ager()->getAccessControlHandler('node')->checkAllGrants($account).
 * When no account argument is passed, the rule substitutes
 * \Drupal::currentUser() to preserve the original default behaviour.
 * Deprecated in drupal:11.3.0 and removed in drupal:12.0.0.
 *
 * Before:
 *   node_access_view_all_nodes($account);
 *
 * After:
 *   \Drupal::entityTypeManager()->getAccessControlHandler('node')->checkAllGrants($account);
 *
 * Caveats:
 *   Calls with more than one argument (invalid usage) are skipped.
 *   Named-argument calls using account: are passed through unchanged
 *   since the target method shares the same parameter name.
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
            'Replace deprecated node_access_view_all_nodes() with \Drupal::entityTypeManager()->getAccessControlHandler(\'node\')->checkAllGrants().',
            [new CodeSample(
                'node_access_view_all_nodes($account);',
                '\Drupal::entityTypeManager()->getAccessControlHandler(\'node\')->checkAllGrants($account);',
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
        if (!$this->isName($node->name, 'node_access_view_all_nodes')) {
            return null;
        }
        if (count($node->args) > 1) {
            return null;
        }

        // Build: \Drupal::entityTypeManager()
        $entityTypeManager = new StaticCall(
            new FullyQualified('Drupal'),
            'entityTypeManager',
        );

        // ->getAccessControlHandler('node')
        $accessControlHandler = new MethodCall(
            $entityTypeManager,
            'getAccessControlHandler',
            [new Arg(new String_('node'))],
        );

        // If $account was passed, use it; otherwise fall back to \Drupal::currentUser().
        if (count($node->args) === 1) {
            $accountArg = $node->args[0];
        } else {
            $accountArg = new Arg(new StaticCall(
                new FullyQualified('Drupal'),
                'currentUser',
            ));
        }

        // ->checkAllGrants($account)
        return new MethodCall(
            $accessControlHandler,
            'checkAllGrants',
            [$accountArg],
        );
    }
}
