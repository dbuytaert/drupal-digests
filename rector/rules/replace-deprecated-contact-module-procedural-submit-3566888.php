<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566888
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces direct calls to the deprecated procedural functions
// contact_user_profile_form_submit() and
// contact_form_user_admin_settings_submit() with equivalent calls via
// \Drupal::service(\Drupal\contact\Hook\ContactFormHooks::class). Both
// functions were deprecated in drupal:11.4.0 and will be removed in
// drupal:12.0.0 as part of migrating contact module logic to OOP hook
// classes.
//
// Before:
//   contact_user_profile_form_submit($form, $form_state);
//   contact_form_user_admin_settings_submit($form, $form_state);
//
// After:
//   \Drupal::service(\Drupal\contact\Hook\ContactFormHooks::class)->profileFormSubmit($form, $form_state);
//   \Drupal::service(\Drupal\contact\Hook\ContactFormHooks::class)->userAdminSettingsSubmit($form, $form_state);


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
 * Replaces deprecated contact module procedural submit handlers with their
 * OOP equivalents on \Drupal\contact\Hook\ContactFormHooks.
 *
 * Deprecated in drupal:11.4.0, removed from drupal:12.0.0.
 * See https://www.drupal.org/node/3566774
 */
final class ReplaceContactDeprecatedFunctionsRector extends AbstractRector
{
    private const REPLACEMENTS = [
        'contact_user_profile_form_submit' => 'profileFormSubmit',
        'contact_form_user_admin_settings_submit' => 'userAdminSettingsSubmit',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated contact_user_profile_form_submit() and contact_form_user_admin_settings_submit() with calls to \\Drupal\\contact\\Hook\\ContactFormHooks service methods.',
            [
                new CodeSample(
                    'contact_user_profile_form_submit($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\contact\\Hook\\ContactFormHooks::class)->profileFormSubmit($form, $form_state);'
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
        if (!$node->name instanceof Name) {
            return null;
        }

        $functionName = $this->getName($node->name);
        if (!array_key_exists($functionName, self::REPLACEMENTS)) {
            return null;
        }

        $methodName = self::REPLACEMENTS[$functionName];

        $hookClass = new FullyQualified('Drupal\\contact\\Hook\\ContactFormHooks');
        $classConst = new ClassConstFetch($hookClass, 'class');

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConst)]
        );

        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
