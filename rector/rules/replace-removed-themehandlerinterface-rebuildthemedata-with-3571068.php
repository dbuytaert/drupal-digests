<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571068
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to ThemeHandlerInterface::rebuildThemeData() (and any
// ThemeHandler instance) to
// \Drupal::service('extension.list.theme')->reset()->getList(). The
// method was deprecated in drupal:10.3.0 and removed in drupal:12.0.0
// (issue #3571068). The direct replacement is the extension.list.theme
// service, which should be reset before fetching the list to reproduce
// the original rebuild behaviour.
//
// Before:
//   $themes = $this->themeHandler->rebuildThemeData();
//
// After:
//   $themes = \Drupal::service('extension.list.theme')->reset()->getList();


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated ThemeHandlerInterface::rebuildThemeData() with
 * \Drupal::service('extension.list.theme')->reset()->getList().
 *
 * rebuildThemeData() was deprecated in drupal:10.3.0 and removed in
 * drupal:12.0.0 (issue #3571068). The replacement is calling the
 * extension.list.theme service directly, resetting it and fetching
 * the list.
 *
 * @see https://www.drupal.org/node/3413196
 * @see https://www.drupal.org/project/drupal/issues/3571068
 */
final class ReplaceRebuildThemeDataRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace removed ThemeHandlerInterface::rebuildThemeData() with \\Drupal::service('extension.list.theme')->reset()->getList()",
            [
                new CodeSample(
                    '$this->themeHandler->rebuildThemeData();',
                    "\\Drupal::service('extension.list.theme')->reset()->getList();"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        if (!$this->isName($node->name, 'rebuildThemeData')) {
            return null;
        }

        // Must be called with no arguments.
        if (!empty($node->args)) {
            return null;
        }

        // Build: \Drupal::service('extension.list.theme')
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('extension.list.theme'))]
        );

        // Build: ->reset()
        $resetCall = new MethodCall($serviceCall, 'reset');

        // Build: ->getList()
        return new MethodCall($resetCall, 'getList');
    }
}
