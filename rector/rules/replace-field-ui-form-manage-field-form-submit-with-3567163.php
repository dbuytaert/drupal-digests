<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3567163
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The procedural function field_ui_form_manage_field_form_submit() was
// the 'Save and manage fields' form submit handler added by field_ui's
// hook_form_alter. It was deprecated in drupal:11.4.0 and removed in
// drupal:12.0.0. The logic now lives in
// \Drupal\field_ui\Hook\FieldUiHooks::manageFieldFormSubmit(), which is
// an autowired service accessible via
// \Drupal::service(FieldUiHooks::class).
//
// Before:
//   field_ui_form_manage_field_form_submit($form, $form_state);
//
// After:
//   \Drupal::service(\Drupal\field_ui\Hook\FieldUiHooks::class)->manageFieldFormSubmit($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

/**
 * Replaces the deprecated procedural function field_ui_form_manage_field_form_submit()
 * with a call to \Drupal\field_ui\Hook\FieldUiHooks::manageFieldFormSubmit() via the
 * service container.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:12.0.0.
 * See https://www.drupal.org/node/3566774
 */
final class ReplaceFieldUiFormManageFieldFormSubmitRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated field_ui_form_manage_field_form_submit() calls with FieldUiHooks::manageFieldFormSubmit()',
            [
                new CodeSample(
                    'field_ui_form_manage_field_form_submit($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\field_ui\\Hook\\FieldUiHooks::class)->manageFieldFormSubmit($form, $form_state);'
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'field_ui_form_manage_field_form_submit')) {
            return null;
        }

        // Build: \Drupal::service(\Drupal\field_ui\Hook\FieldUiHooks::class)->manageFieldFormSubmit(...)
        $drupalClass = new Node\Name\FullyQualified('Drupal');
        $serviceCall = new Node\Expr\StaticCall(
            $drupalClass,
            'service',
            [
                new Node\Arg(
                    new Node\Expr\ClassConstFetch(
                        new Node\Name\FullyQualified('Drupal\\field_ui\\Hook\\FieldUiHooks'),
                        'class'
                    )
                ),
            ]
        );

        return new Node\Expr\MethodCall(
            $serviceCall,
            'manageFieldFormSubmit',
            $node->args
        );
    }
}
