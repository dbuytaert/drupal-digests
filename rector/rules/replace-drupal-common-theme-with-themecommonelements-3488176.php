<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3488176
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The procedural function drupal_common_theme() was removed in Drupal
// 11.1 when system_theme was converted to an OOP hook class. Its content
// was moved to the static method
// \Drupal\Core\Theme\ThemeCommonElements::commonElements(). Any contrib
// or custom code that merges drupal_common_theme() into a hook_theme()
// implementation must call the new static method instead.
//
// Before:
//   return array_merge(drupal_common_theme(), [
//     'mymodule_widget' => [
//       'render element' => 'element',
//     ],
//   ]);
//
// After:
//   return array_merge(\Drupal\Core\Theme\ThemeCommonElements::commonElements(), [
//     'mymodule_widget' => [
//       'render element' => 'element',
//     ],
//   ]);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceDrupalCommonThemeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace drupal_common_theme() with ThemeCommonElements::commonElements()',
            [
                new CodeSample(
                    'drupal_common_theme()',
                    '\\Drupal\\Core\\Theme\\ThemeCommonElements::commonElements()'
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
        if (!$this->isName($node, 'drupal_common_theme')) {
            return null;
        }

        return new StaticCall(
            new FullyQualified('Drupal\\Core\\Theme\\ThemeCommonElements'),
            new Identifier('commonElements'),
            []
        );
    }
}
