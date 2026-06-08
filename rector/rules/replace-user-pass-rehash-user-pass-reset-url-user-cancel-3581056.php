<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces three deprecated global functions from the user module —
 * user_pass_rehash(), user_pass_reset_url(), and user_cancel_url() —
 * with equivalent method calls on \Drupal\user\OneTimeAuthentication
 * obtained via \Drupal::service(). The old functions were deprecated in
 * Drupal 11.4.0 and will be removed in 13.0.0. Contrib modules that
 * build password-reset or account-cancellation links call these
 * functions directly and gain an automatic upgrade.
 *
 * Before:
 *   $hash = user_pass_rehash($account, $timestamp);
 *   $reset = user_pass_reset_url($account);
 *   $cancel = user_cancel_url($account, $options);
 *
 * After:
 *   $hash = \Drupal::service(\Drupal\user\OneTimeAuthentication::class)->generateHmac($account, $timestamp);
 *   $reset = \Drupal::service(\Drupal\user\OneTimeAuthentication::class)->generateOneTimeLoginUrl($account)->toString();
 *   $cancel = \Drupal::service(\Drupal\user\OneTimeAuthentication::class)->generateCancelConfirmUrl($account, $options)->toString();
 *
 * Caveats:
 *   The user_mail_tokens() function (deprecated in the same issue) is
 *   not handled: it is nearly always used as a string callback value
 *   'callback' => 'user_mail_tokens' rather than called directly, and
 *   its replacement requires a fourth BubbleableMetadata argument that
 *   cannot be safely synthesised. Sites using it as a string callback
 *   must update it manually to \Drupal::service(\Drupal\user\OneTimeAut
 *   hentication::class)->tokens(...).
 *
 * @see https://www.drupal.org/node/3581056
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

final class ReplaceUserOneTimeAuthFunctionsRector extends AbstractRector
{
    // Maps deprecated function name => [new method name, needs ->toString() wrapper]
    private const FUNCTION_MAP = [
        'user_pass_rehash'   => ['generateHmac', false],
        'user_pass_reset_url' => ['generateOneTimeLoginUrl', true],
        'user_cancel_url'    => ['generateCancelConfirmUrl', true],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated user one-time authentication global functions with methods on the OneTimeAuthentication service.',
            [new CodeSample(
                'user_pass_rehash($account, $timestamp);',
                '\\Drupal::service(\\Drupal\\user\\OneTimeAuthentication::class)->generateHmac($account, $timestamp);',
            )]
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
        $funcName = $this->getName($node->name);
        if ($funcName === null || !array_key_exists($funcName, self::FUNCTION_MAP)) {
            return null;
        }
        [$methodName, $needsToString] = self::FUNCTION_MAP[$funcName];

        // \Drupal::service(\Drupal\user\OneTimeAuthentication::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified('Drupal\\user\\OneTimeAuthentication'),
                new Identifier('class')
            ))]
        );

        $methodCall = new MethodCall($serviceCall, $methodName, $node->args);

        if ($needsToString) {
            return new MethodCall($methodCall, 'toString', []);
        }

        return $methodCall;
    }
}
