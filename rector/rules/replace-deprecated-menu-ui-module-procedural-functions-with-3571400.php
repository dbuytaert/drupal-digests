<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571400
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites six procedural functions from menu_ui.module deprecated in
// drupal:11.4.0 and removed in drupal:12.0.0 or drupal:13.0.0 (issue
// #3571400). _menu_ui_node_save() and menu_ui_get_menu_link_defaults()
// become calls on \Drupal::service(MenuUiUtility::class). The four hook
// callbacks menu_ui_node_builder(), menu_ui_form_node_form_submit(),
// menu_ui_form_node_type_form_validate(), and
// menu_ui_form_node_type_form_builder() become calls on
// \Drupal::service(MenuUiHooks::class). All arguments are preserved.
//
// Before:
//   _menu_ui_node_save($node, $values);
//   $defaults = menu_ui_get_menu_link_defaults($node);
//   menu_ui_form_node_form_submit($form, $form_state);
//
// After:
//   \Drupal::service(\Drupal\menu_ui\MenuUiUtility::class)->menuUiNodeSave($node, $values);
//   $defaults = \Drupal::service(\Drupal\menu_ui\MenuUiUtility::class)->getMenuLinkDefaults($node);
//   \Drupal::service(\Drupal\menu_ui\Hook\MenuUiHooks::class)->formNodeFormSubmit($form, $form_state);


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
 * Replaces deprecated procedural functions from menu_ui.module with service calls.
 *
 * Six procedural functions in menu_ui.module were deprecated in drupal:11.4.0
 * and are removed in drupal:12.0.0 or drupal:13.0.0 (issue #3571400).
 *
 * _menu_ui_node_save($node, $values) and menu_ui_get_menu_link_defaults($node)
 * are replaced by calls on \Drupal::service(MenuUiUtility::class).
 *
 * The four hook callbacks menu_ui_node_builder(), menu_ui_form_node_form_submit(),
 * menu_ui_form_node_type_form_validate(), and menu_ui_form_node_type_form_builder()
 * are replaced by calls on \Drupal::service(MenuUiHooks::class).
 *
 * @see https://www.drupal.org/node/3566774
 * @see https://www.drupal.org/project/drupal/issues/3571400
 */
final class ReplaceDeprecatedMenuUiFunctionsRector extends AbstractRector
{
    private const MENU_UI_UTILITY = 'Drupal\\menu_ui\\MenuUiUtility';
    private const MENU_UI_HOOKS   = 'Drupal\\menu_ui\\Hook\\MenuUiHooks';

    /**
     * Map: deprecated function name => [service FQCN, replacement method name].
     */
    private const FUNC_MAP = [
        '_menu_ui_node_save'                   => [self::MENU_UI_UTILITY, 'menuUiNodeSave'],
        'menu_ui_get_menu_link_defaults'       => [self::MENU_UI_UTILITY, 'getMenuLinkDefaults'],
        'menu_ui_node_builder'                 => [self::MENU_UI_HOOKS,   'nodeBuilder'],
        'menu_ui_form_node_form_submit'        => [self::MENU_UI_HOOKS,   'formNodeFormSubmit'],
        'menu_ui_form_node_type_form_validate' => [self::MENU_UI_HOOKS,   'formNodeTypeFormValidate'],
        'menu_ui_form_node_type_form_builder'  => [self::MENU_UI_HOOKS,   'formNodeTypeFormBuilder'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated procedural functions from menu_ui.module with MenuUiUtility or MenuUiHooks service calls',
            [
                new CodeSample(
                    '_menu_ui_node_save($node, $values);',
                    '\\Drupal::service(\\Drupal\\menu_ui\\MenuUiUtility::class)->menuUiNodeSave($node, $values);'
                ),
                new CodeSample(
                    'menu_ui_get_menu_link_defaults($node);',
                    '\\Drupal::service(\\Drupal\\menu_ui\\MenuUiUtility::class)->getMenuLinkDefaults($node);'
                ),
                new CodeSample(
                    'menu_ui_form_node_form_submit($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\menu_ui\\Hook\\MenuUiHooks::class)->formNodeFormSubmit($form, $form_state);'
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
        if (!$node instanceof FuncCall || !$node->name instanceof Name) {
            return null;
        }

        $funcName = $node->name->toString();

        if (!isset(self::FUNC_MAP[$funcName])) {
            return null;
        }

        [$serviceFqcn, $methodName] = self::FUNC_MAP[$funcName];

        // Build: \Drupal::service(ServiceClass::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified($serviceFqcn),
                'class'
            ))]
        );

        // Build: ->replacementMethod(...original args...)
        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
