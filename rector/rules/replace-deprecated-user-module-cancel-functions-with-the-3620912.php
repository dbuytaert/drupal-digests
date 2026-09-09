<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the global functions user_cancel(),
 * _user_cancel(), _user_cancel_session_regenerate(), and
 * user_cancel_methods() in favor of the new
 * Drupal\user\AccountCancellation service, which exposes equivalent
 * cancel(), cancelAccount(), regenerateSession(), and cancelMethods()
 * methods. This rule rewrites direct calls to these global functions
 * into calls on the service fetched via
 * \Drupal::service(AccountCancellation::class), so contrib and custom
 * code invoking user account cancellation continues to work once the
 * functions are removed in Drupal 13.
 *
 * Before:
 *   user_cancel($edit, $uid, $method);
 *   _user_cancel($edit, $account, $method);
 *   _user_cancel_session_regenerate();
 *   $methods = user_cancel_methods();
 *
 * After:
 *   \Drupal::service(\Drupal\user\AccountCancellation::class)->cancel($edit, $uid, $method);
 *   \Drupal::service(\Drupal\user\AccountCancellation::class)->cancelAccount($edit, $account, $method);
 *   \Drupal::service(\Drupal\user\AccountCancellation::class)->regenerateSession();
 *   $methods = \Drupal::service(\Drupal\user\AccountCancellation::class)->cancelMethods();
 *
 * Caveats:
 *   Only rewrites direct calls to these four global function names;
 *   dynamic calls ($fn(), call_user_func()) and same-named
 *   methods/static calls on unrelated classes are left untouched since
 *   they cannot be safely disambiguated at the AST level.
 *
 * @see https://www.drupal.org/node/3620912
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUserCancelFunctionsWithAccountCancellationRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const FUNCTION_TO_METHOD = [
        'user_cancel' => 'cancel',
        '_user_cancel' => 'cancelAccount',
        '_user_cancel_session_regenerate' => 'regenerateSession',
        'user_cancel_methods' => 'cancelMethods',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated user.module account cancellation functions with the AccountCancellation service.',
            [new CodeSample(
                'user_cancel($edit, $uid, $method);',
                "\Drupal::service(\Drupal\user\AccountCancellation::class)->cancel(\$edit, \$uid, \$method);",
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
        if ($node->name instanceof Node\Expr) {
            // Dynamic function calls (e.g. $fn()) are never a match.
            return null;
        }
        $functionName = $this->getName($node->name);
        if ($functionName === null || !isset(self::FUNCTION_TO_METHOD[$functionName])) {
            return null;
        }

        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($this->nodeFactory->createClassConstFetch('Drupal\\user\\AccountCancellation', 'class'))],
        );

        return new MethodCall($service, self::FUNCTION_TO_METHOD[$functionName], $node->args);
    }
}
