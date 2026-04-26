<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3572243
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites five Views procedural functions deprecated in drupal:11.4.0
// and removed in drupal:13.0.0 (issue #3572243):
// views_view_is_enabled($view) becomes $view->status(),
// views_view_is_disabled($view) becomes !$view->status(),
// views_enable_view($view) becomes $view->enable()->save(),
// views_disable_view($view) becomes $view->disable()->save(), and
// views_get_view_result(...) becomes
// \Drupal\views\Views::getViewResult(...). Functions without a 1:1
// replacement are left for manual migration.
//
// Before:
//   views_view_is_enabled($view);
//   views_view_is_disabled($view);
//   views_enable_view($view);
//   views_disable_view($view);
//   $result = views_get_view_result('my_view', 'page_1', 'arg1');
//
// After:
//   $view->status();
//   !$view->status();
//   $view->enable()->save();
//   $view->disable()->save();
//   $result = \Drupal\views\Views::getViewResult('my_view', 'page_1', 'arg1');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated Views procedural functions with their equivalents.
 *
 * Several functions in views.module were deprecated in drupal:11.4.0 and are
 * removed in drupal:13.0.0 (issue #3572243):
 *
 *   views_view_is_enabled($view)   => $view->status()
 *   views_view_is_disabled($view)  => !$view->status()
 *   views_enable_view($view)       => $view->enable()->save()
 *   views_disable_view($view)      => $view->disable()->save()
 *   views_get_view_result(...)     => Views::getViewResult(...)
 *
 * Functions with no replacement (views_set_current_view, views_get_current_view,
 * _views_query_tag_alter_condition, views_element_validate_tags) and the
 * render-element migration for views_embed_view are out of scope for
 * automatic rewriting.
 *
 * @see https://www.drupal.org/node/3572594
 * @see https://www.drupal.org/project/drupal/issues/3572243
 */
final class ReplaceDeprecatedViewsFunctionsRector extends AbstractRector
{
    private const VIEWS_CLASS = 'Drupal\\views\\Views';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated views procedural functions with object-oriented equivalents',
            [
                new CodeSample(
                    'views_view_is_enabled($view);',
                    '$view->status();'
                ),
                new CodeSample(
                    'views_view_is_disabled($view);',
                    '!$view->status();'
                ),
                new CodeSample(
                    'views_enable_view($view);',
                    '$view->enable()->save();'
                ),
                new CodeSample(
                    'views_disable_view($view);',
                    '$view->disable()->save();'
                ),
                new CodeSample(
                    'views_get_view_result($name, $display_id, ...$args);',
                    '\\Drupal\\views\\Views::getViewResult($name, $display_id, ...$args);'
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

        return match ($funcName) {
            'views_view_is_enabled'  => $this->refactorIsEnabled($node),
            'views_view_is_disabled' => $this->refactorIsDisabled($node),
            'views_enable_view'      => $this->refactorEnableView($node),
            'views_disable_view'     => $this->refactorDisableView($node),
            'views_get_view_result'  => $this->refactorGetViewResult($node),
            default                  => null,
        };
    }

    /**
     * views_view_is_enabled($view) => $view->status()
     */
    private function refactorIsEnabled(FuncCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }
        $viewArg = $node->args[0]->value;
        return new MethodCall($viewArg, new Identifier('status'));
    }

    /**
     * views_view_is_disabled($view) => !$view->status()
     */
    private function refactorIsDisabled(FuncCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }
        $viewArg = $node->args[0]->value;
        return new BooleanNot(new MethodCall($viewArg, new Identifier('status')));
    }

    /**
     * views_enable_view($view) => $view->enable()->save()
     */
    private function refactorEnableView(FuncCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }
        $viewArg = $node->args[0]->value;
        $enableCall = new MethodCall($viewArg, new Identifier('enable'));
        return new MethodCall($enableCall, new Identifier('save'));
    }

    /**
     * views_disable_view($view) => $view->disable()->save()
     */
    private function refactorDisableView(FuncCall $node): ?Node
    {
        if (count($node->args) < 1) {
            return null;
        }
        $viewArg = $node->args[0]->value;
        $disableCall = new MethodCall($viewArg, new Identifier('disable'));
        return new MethodCall($disableCall, new Identifier('save'));
    }

    /**
     * views_get_view_result($name, $display_id, ...$args) =>
     *   Views::getViewResult($name, $display_id, ...$args)
     */
    private function refactorGetViewResult(FuncCall $node): ?Node
    {
        return new StaticCall(
            new FullyQualified(self::VIEWS_CLASS),
            new Identifier('getViewResult'),
            $node->args
        );
    }
}
