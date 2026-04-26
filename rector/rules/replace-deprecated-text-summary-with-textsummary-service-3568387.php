<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3568387
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the global text_summary() function, deprecated in
// Drupal 11.4.0 and removed in 13.0.0, with the equivalent
// \Drupal::service(\Drupal\text\TextSummary::class)->generate() call.
// All arguments (text, format, size) are forwarded as-is, preserving
// existing behaviour without requiring any manual configuration.
//
// Before:
//   $summary = text_summary($body, 'basic_html', 300);
//
// After:
//   $summary = \Drupal::service(\Drupal\text\TextSummary::class)->generate($body, 'basic_html', 300);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

/**
 * Replaces deprecated text_summary() calls with the TextSummary service.
 *
 * @see https://www.drupal.org/node/3568389
 */
final class TextSummaryFunctionToServiceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated text_summary() calls with \Drupal::service(\Drupal\text\TextSummary::class)->generate()',
            [
                new CodeSample(
                    'text_summary($text, $format, $size);',
                    '\Drupal::service(\Drupal\text\TextSummary::class)->generate($text, $format, $size);'
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

        if (!$this->isName($node, 'text_summary')) {
            return null;
        }

        // Build \Drupal::service(\Drupal\text\TextSummary::class)
        $drupalClass = new FullyQualified('Drupal');
        $serviceArg = new Arg(
            new ClassConstFetch(
                new FullyQualified('Drupal\\text\\TextSummary'),
                'class'
            )
        );
        $serviceCall = new StaticCall($drupalClass, 'service', [$serviceArg]);

        // Preserve all arguments from the original text_summary() call.
        $args = $node->args;

        // Build \Drupal::service(...)->generate(...)
        return new MethodCall($serviceCall, 'generate', $args);
    }
}
