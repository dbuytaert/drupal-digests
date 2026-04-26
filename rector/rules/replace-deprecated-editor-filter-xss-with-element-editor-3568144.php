<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3568144
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4 deprecated the procedural editor_filter_xss() function
// (removed in 13.0) and moved its logic to
// \Drupal\editor\Element::filterXss() on the element.editor service.
// This rule rewrites every call to the old function into the equivalent
// \Drupal::service('element.editor')->filterXss(...) call, preserving
// all arguments.
//
// Before:
//   return editor_filter_xss($html, $format, $originalFormat);
//
// After:
//   return \Drupal::service('element.editor')->filterXss($html, $format, $originalFormat);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated editor_filter_xss() with the element.editor service method.
 *
 * @see https://www.drupal.org/node/3568144
 */
final class EditorFilterXssRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated editor_filter_xss() with \\Drupal::service('element.editor')->filterXss()",
            [
                new CodeSample(
                    "editor_filter_xss(\$html, \$format, \$originalFormat);",
                    "\\Drupal::service('element.editor')->filterXss(\$html, \$format, \$originalFormat);",
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'editor_filter_xss')) {
            return null;
        }

        // Build \Drupal::service('element.editor')
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('element.editor'))]
        );

        // Build ->filterXss(...original args...)
        return new MethodCall(
            $serviceCall,
            'filterXss',
            $node->args
        );
    }
}
