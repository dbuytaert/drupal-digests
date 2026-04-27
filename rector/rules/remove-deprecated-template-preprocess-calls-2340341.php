<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/2340341
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes direct calls to the deprecated template_preprocess() function
// introduced in Drupal 11.2.0 (removed in 12.0.0). Since 11.2.0,
// ThemeManager::render() automatically adds the default template
// variables (attributes, title_attributes, logged_in, etc.) before any
// preprocess hooks run, making explicit template_preprocess() calls in
// preprocess functions unnecessary.
//
// Before:
//   function mymodule_preprocess_mytemplate(array &$variables): void {
//       template_preprocess($variables, 'mytemplate', $info);
//       $variables['my_var'] = 'value';
//   }
//
// After:
//   function mymodule_preprocess_mytemplate(array &$variables): void {
//       $variables['my_var'] = 'value';
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated template_preprocess() calls.
 *
 * Since Drupal 11.2.0 the default template variables are added automatically
 * by ThemeManager::render() before any preprocess hooks run, making explicit
 * calls to template_preprocess() unnecessary.
 */
final class RemoveTemplatePreprocessCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated template_preprocess() calls. Since Drupal 11.2.0 the default preprocess variables are added automatically by ThemeManager::render() before preprocess hooks run, so explicit calls are no longer needed.',
            [
                new CodeSample(
                    'function mymodule_preprocess_mytemplate(array &$variables): void {
    template_preprocess($variables, \'mytemplate\', $info);
    $variables[\'my_var\'] = \'value\';
}',
                    'function mymodule_preprocess_mytemplate(array &$variables): void {
    $variables[\'my_var\'] = \'value\';
}'
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node)
    {
        if (!$node->expr instanceof FuncCall) {
            return null;
        }

        if (!$this->isName($node->expr, 'template_preprocess')) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
