<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3582106
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites direct calls to user_form_process_password_confirm(),
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0, to
// \Drupal::service(UserThemeHooks::class)->processPasswordConfirm(). The
// function was moved from user.module into the UserThemeHooks OOP hook
// class as part of the ongoing procedural-to-OOP hook migration.
//
// Before:
//   return user_form_process_password_confirm($element);
//
// After:
//   return \Drupal::service(\Drupal\user\Hook\UserThemeHooks::class)->processPasswordConfirm($element);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces user_form_process_password_confirm() with
 * \Drupal::service(UserThemeHooks::class)->processPasswordConfirm().
 *
 * The procedural function was deprecated in drupal:11.4.0 and removed in
 * drupal:13.0.0. The authoritative replacement is the processPasswordConfirm()
 * method on the UserThemeHooks service.
 */
final class ReplaceUserFormProcessPasswordConfirmRector extends AbstractRector
{
    private const DEPRECATED_FUNCTION = 'user_form_process_password_confirm';
    private const USER_THEME_HOOKS_CLASS = 'Drupal\\user\\Hook\\UserThemeHooks';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated user_form_process_password_confirm() with UserThemeHooks::processPasswordConfirm() service call',
            [
                new CodeSample(
                    'user_form_process_password_confirm($element);',
                    '\\Drupal::service(\\Drupal\\user\\Hook\\UserThemeHooks::class)->processPasswordConfirm($element);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }

        if (!$node->name instanceof Name) {
            return null;
        }

        if ($node->name->toString() !== self::DEPRECATED_FUNCTION) {
            return null;
        }

        // Build: \Drupal::service(\Drupal\user\Hook\UserThemeHooks::class)
        $classConstFetch = new ClassConstFetch(
            new FullyQualified(self::USER_THEME_HOOKS_CLASS),
            'class'
        );

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConstFetch)]
        );

        // Build: ->processPasswordConfirm($element)
        return new MethodCall(
            $serviceCall,
            'processPasswordConfirm',
            $node->args
        );
    }
}
