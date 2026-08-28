<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the procedural user_login_finalize() and
 * user_logout() functions in user.module in favor of
 * \Drupal\user\LoginFinalizer::finalizeLogin() and
 * \Drupal\user\LogoutFinalizer::finalizeLogout(), obtained via
 * dependency injection or \Drupal::service(). This rule rewrites call
 * sites of both global functions to the equivalent service call, letting
 * contrib and custom code (including auth/session modules that call
 * these directly) migrate ahead of removal in drupal:13.0.0.
 *
 * Before:
 *   user_login_finalize($account);
 *   user_logout();
 *
 * After:
 *   \Drupal::service(\Drupal\user\LoginFinalizer::class)->finalizeLogin($account);
 *   \Drupal::service(\Drupal\user\LogoutFinalizer::class)->finalizeLogout();
 *
 * Caveats:
 *   Skips call sites that use named arguments (e.g.
 *   user_login_finalize(account: $account)) because the new service
 *   method's parameter is named $user, not $account; rewriting those
 *   would break under strict named-argument binding. Such (rare) call
 *   sites are left untouched for manual review. The rule targets
 *   \Drupal::service() call sites; it does not inject the service into
 *   a class constructor, since that requires editing the class's
 *   dependency list which is outside the scope of this rewrite.
 *
 * @see https://www.drupal.org/node/2012976
 * @deprecated drupal:11.5.0
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

final class ReplaceUserLoginLogoutFinalizeRector extends AbstractRector
{
    /**
     * Maps deprecated global function name to [service FQCN, method name].
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const REPLACEMENTS = [
        'user_login_finalize' => ['Drupal\\user\\LoginFinalizer', 'finalizeLogin'],
        'user_logout' => ['Drupal\\user\\LogoutFinalizer', 'finalizeLogout'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated user_login_finalize() and user_logout() global functions with the LoginFinalizer and LogoutFinalizer services.',
            [new CodeSample(
                'user_login_finalize($account);
user_logout();',
                '\Drupal::service(\Drupal\user\LoginFinalizer::class)->finalizeLogin($account);
\Drupal::service(\Drupal\user\LogoutFinalizer::class)->finalizeLogout();',
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

        foreach (self::REPLACEMENTS as $functionName => [$serviceClass, $methodName]) {
            if (!$this->isName($node->name, $functionName)) {
                continue;
            }

            // The new service method's parameter name differs from the old
            // function's parameter name; a named argument would break.
            foreach ($node->args as $arg) {
                if ($arg instanceof Arg && $arg->name !== null) {
                    return null;
                }
            }

            $serviceCall = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new ClassConstFetch(new FullyQualified($serviceClass), 'class'))],
            );

            return new MethodCall($serviceCall, $methodName, $node->args);
        }

        return null;
    }
}
