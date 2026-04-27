<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3035340
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the eight procedural functions deprecated in drupal:11.4.0
// and removed in drupal:13.0.0 from core/modules/views_ui/admin.inc.
// Functions whose trait replacements are public static
// (views_ui_form_button_was_clicked, views_ui_add_limited_validation,
// views_ui_add_ajax_wrapper, views_ui_nojs_submit) are converted to
// fully-qualified static calls on ViewsFormHelperTrait or
// ViewsFormAjaxHelperTrait. Protected or public instance methods
// (views_ui_standard_display_dropdown, views_ui_build_form_url,
// views_ui_add_ajax_trigger, views_ui_ajax_update_form) are converted to
// $this->method() calls; the calling class must also add the appropriate
// use statement.
//
// Before:
//   views_ui_standard_display_dropdown($form, $form_state, $section);
//   $url = views_ui_build_form_url($form_state);
//   views_ui_add_ajax_trigger($form['show'], 'type', ['displays']);
//   views_ui_form_button_was_clicked($element, $form_state);
//   views_ui_add_limited_validation($element, $form_state);
//   views_ui_add_ajax_wrapper($element, $form_state);
//   views_ui_nojs_submit($form, $form_state);
//
// After:
//   $this->standardDisplayDropdown($form, $form_state, $section);
//   $url = $this->buildFormUrl($form_state);
//   $this->addAjaxTrigger($form['show'], 'type', ['displays']);
//   \Drupal\views\ViewsFormHelperTrait::formButtonWasClicked($element, $form_state);
//   \Drupal\views\ViewsFormAjaxHelperTrait::addLimitedValidation($element, $form_state);
//   \Drupal\views\ViewsFormAjaxHelperTrait::addAjaxWrapper($element, $form_state);
//   \Drupal\views\ViewsFormAjaxHelperTrait::noJsSubmit($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated views_ui/admin.inc procedural functions with trait methods.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:13.0.0.
 * See https://www.drupal.org/node/3040111
 */
final class ViewsUiAdminDeprecatedFunctionsRector extends AbstractRector
{
    /**
     * Functions that became public static methods (safe as direct static calls).
     */
    private const STATIC_MAP = [
        'views_ui_form_button_was_clicked' => [
            'class'  => 'Drupal\\views\\ViewsFormHelperTrait',
            'method' => 'formButtonWasClicked',
        ],
        'views_ui_add_limited_validation' => [
            'class'  => 'Drupal\\views\\ViewsFormAjaxHelperTrait',
            'method' => 'addLimitedValidation',
        ],
        'views_ui_add_ajax_wrapper' => [
            'class'  => 'Drupal\\views\\ViewsFormAjaxHelperTrait',
            'method' => 'addAjaxWrapper',
        ],
        'views_ui_nojs_submit' => [
            'class'  => 'Drupal\\views\\ViewsFormAjaxHelperTrait',
            'method' => 'noJsSubmit',
        ],
    ];

    /**
     * Functions that became instance methods (must be called via $this->).
     * The calling class must also add the appropriate trait via `use`.
     */
    private const INSTANCE_MAP = [
        'views_ui_standard_display_dropdown' => 'standardDisplayDropdown',
        'views_ui_build_form_url'            => 'buildFormUrl',
        'views_ui_add_ajax_trigger'          => 'addAjaxTrigger',
        'views_ui_ajax_update_form'          => 'ajaxUpdateForm',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replaces deprecated views_ui/admin.inc procedural functions (deprecated in drupal:11.4.0, removed in drupal:13.0.0) with calls to ViewsFormHelperTrait or ViewsFormAjaxHelperTrait methods.',
            [
                new CodeSample(
                    'views_ui_form_button_was_clicked($element, $form_state);',
                    '\\Drupal\\views\\ViewsFormHelperTrait::formButtonWasClicked($element, $form_state);'
                ),
                new CodeSample(
                    'views_ui_standard_display_dropdown($form, $form_state, $section);',
                    '$this->standardDisplayDropdown($form, $form_state, $section);'
                ),
            ]
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
        $name = $this->getName($node->name);
        if ($name === null) {
            return null;
        }

        // Static replacements: FuncCall -> StaticCall on the trait class.
        if (isset(self::STATIC_MAP[$name])) {
            $map = self::STATIC_MAP[$name];
            return new StaticCall(
                new FullyQualified($map['class']),
                $map['method'],
                $node->args
            );
        }

        // Instance replacements: FuncCall -> $this->method().
        if (isset(self::INSTANCE_MAP[$name])) {
            return new MethodCall(
                new Variable('this'),
                self::INSTANCE_MAP[$name],
                $node->args
            );
        }

        return null;
    }
}
