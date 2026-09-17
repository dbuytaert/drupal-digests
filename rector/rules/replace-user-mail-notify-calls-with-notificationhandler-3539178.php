<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the global function _user_mail_notify($op,
 * $account) in favor of dedicated methods on the internal
 * Drupal\user\NotificationHandler service, one method per notification
 * type. This rule rewrites call sites where the $op argument is a
 * literal string it recognizes (e.g. password_reset, cancel_confirm,
 * status_blocked) into the equivalent
 * \Drupal::service(NotificationHandler::class)->sendXxx($account) call,
 * letting contrib modules migrate ahead of removal in Drupal 13.0.0.
 *
 * Before:
 *   _user_mail_notify('password_reset', $account);
 *   _user_mail_notify('cancel_confirm', $entity);
 *
 * After:
 *   \Drupal::service(\Drupal\user\NotificationHandler::class)->sendPasswordReset($account);
 *   \Drupal::service(\Drupal\user\NotificationHandler::class)->sendCancelConfirm($entity);
 *
 * Caveats:
 *   Only rewrites calls whose $op argument is a plain string literal
 *   matching one of the eight known operations (register_admin_created,
 *   register_no_approval_required, register_pending_approval,
 *   password_reset, status_activated, status_blocked, cancel_confirm,
 *   status_canceled). Calls that compute $op dynamically (e.g. $op =
 *   $active ? 'status_activated' : 'status_blocked';
 *   _user_mail_notify($op, $account);), use named arguments, pass a
 *   custom/unsupported $op value, or pass a different argument count
 *   are left untouched and must be migrated by hand. The old function
 *   could return NULL (suppressed) or FALSE (error) while the new
 *   methods always return bool; call sites that rely on distinguishing
 *   NULL from FALSE (rather than a simple truthy/falsy check) need
 *   manual review.
 *
 * @see https://www.drupal.org/node/3539178
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
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUserMailNotifyRector extends AbstractRector
{
    private const OP_TO_METHOD = [
        'register_admin_created' => 'sendRegisterAdminCreated',
        'register_no_approval_required' => 'sendRegisterNoApprovalRequired',
        'register_pending_approval' => 'sendRegisterPendingApproval',
        'password_reset' => 'sendPasswordReset',
        'status_activated' => 'sendStatusActivated',
        'status_blocked' => 'sendStatusBlocked',
        'cancel_confirm' => 'sendCancelConfirm',
        'status_canceled' => 'sendStatusCanceled',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace _user_mail_notify() calls with the equivalent Drupal\user\NotificationHandler method.',
            [new CodeSample(
                "_user_mail_notify('password_reset', \$account);",
                "\\Drupal::service(\\Drupal\\user\\NotificationHandler::class)->sendPasswordReset(\$account);",
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
        if (!$this->isName($node->name, '_user_mail_notify')) {
            return null;
        }
        if (count($node->args) !== 2) {
            return null;
        }
        if (!$node->args[0] instanceof Arg || !$node->args[1] instanceof Arg) {
            return null;
        }
        // Named arguments change the calling convention; skip for safety.
        if ($node->args[0]->name !== null || $node->args[1]->name !== null) {
            return null;
        }
        $opArg = $node->args[0]->value;
        if (!$opArg instanceof String_) {
            // The $op argument is not a plain string literal (e.g. a
            // variable); cannot determine which NotificationHandler method
            // to call without evaluating runtime data.
            return null;
        }
        if (!isset(self::OP_TO_METHOD[$opArg->value])) {
            // Unknown / custom $op value; no equivalent method exists on
            // NotificationHandler.
            return null;
        }
        $method = self::OP_TO_METHOD[$opArg->value];
        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified('Drupal\\user\\NotificationHandler'), 'class'))],
        );
        return new MethodCall($service, $method, [$node->args[1]]);
    }
}
