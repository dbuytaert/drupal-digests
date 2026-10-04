<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * DoTrustedCallbackTrait::doTrustedCallback() and
 * StaticTrustedCallbackHelper::callback() dropped their $error_type
 * parameter; the slot is now $extra_trusted_interface. Calls still
 * passing TrustedCallbackInterface::THROW_EXCEPTION or
 * TrustedCallbackInterface::TRIGGER_SILENCED_DEPRECATION as the 4th
 * positional argument are rewritten to drop it, shifting a trailing
 * $extra_trusted_interface argument into its place. This avoids both the
 * new deprecation notice and a latent bug where the removed argument
 * would otherwise be misinterpreted as the new parameter.
 *
 * Before:
 *   $this->doTrustedCallback($callback, $args, $message, TrustedCallbackInterface::THROW_EXCEPTION, RenderCallbackInterface::class);
 *   \Drupal\Core\Security\StaticTrustedCallbackHelper::callback($callback, $args, $message, TrustedCallbackInterface::THROW_EXCEPTION);
 *
 * After:
 *   $this->doTrustedCallback($callback, $args, $message, RenderCallbackInterface::class);
 *   \Drupal\Core\Security\StaticTrustedCallbackHelper::callback($callback, $args, $message);
 *
 * Caveats:
 *   The rule only fires when the 4th positional argument is literally
 *   TrustedCallbackInterface::THROW_EXCEPTION or
 *   ::TRIGGER_SILENCED_DEPRECATION; it does not type-check the
 *   MethodCall receiver (the trait is mixed into arbitrary unrelated
 *   classes with no common interface), relying instead on the
 *   distinctive method name plus the literal constant reference for
 *   safety. Calls using named arguments or argument unpacking are left
 *   untouched since they are already compatible with the new signature.
 *
 * @see https://www.drupal.org/node/3081025
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveTrustedCallbackErrorTypeArgRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the deprecated $error_type argument from DoTrustedCallbackTrait::doTrustedCallback() and StaticTrustedCallbackHelper::callback() calls.',
            [new CodeSample(
                '$this->doTrustedCallback($callback, $args, $message, TrustedCallbackInterface::THROW_EXCEPTION, RenderCallbackInterface::class);',
                '$this->doTrustedCallback($callback, $args, $message, RenderCallbackInterface::class);',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /** @param MethodCall|StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            if (!$this->isName($node->name, 'doTrustedCallback')) {
                return null;
            }
        } elseif ($node instanceof StaticCall) {
            if (!$this->isName($node->class, 'Drupal\\Core\\Security\\StaticTrustedCallbackHelper')) {
                return null;
            }
            if (!$this->isName($node->name, 'callback')) {
                return null;
            }
        } else {
            return null;
        }

        if ($node->isFirstClassCallable()) {
            return null;
        }

        $args = $node->args;
        if (count($args) !== 4 && count($args) !== 5) {
            return null;
        }
        foreach ($args as $arg) {
            if ($arg->name !== null || $arg->unpack) {
                return null;
            }
        }

        if (!$this->isTrustedCallbackErrorTypeArg($args[3]->value)) {
            return null;
        }

        unset($node->args[3]);
        $node->args = array_values($node->args);

        return $node;
    }

    private function isTrustedCallbackErrorTypeArg(Node $value): bool
    {
        if (!$value instanceof ClassConstFetch) {
            return false;
        }
        if (!$this->isName($value->class, 'Drupal\\Core\\Security\\TrustedCallbackInterface')) {
            return false;
        }
        $constantName = $this->getName($value->name);
        return $constantName === 'THROW_EXCEPTION' || $constantName === 'TRIGGER_SILENCED_DEPRECATION';
    }
}
