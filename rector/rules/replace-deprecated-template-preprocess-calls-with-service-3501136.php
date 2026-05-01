<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.2.0 deprecated eight global template_preprocess_*()
 * functions (block, container, html, links, page, time, datetime_form,
 * datetime_wrapper). Each function now delegates to a method on a core
 * service (ThemePreprocess, DatePreprocess, or BlockHooks). This rule
 * rewrites any direct calls to these deprecated functions into the
 * equivalent \Drupal::service(...)->method() call.
 *
 * Before:
 *   template_preprocess_block($variables);
 *
 * After:
 *   \Drupal::service(\Drupal\block\Hook\BlockHooks::class)->preprocessBlock($variables);
 *
 * @see https://www.drupal.org/node/3501136
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated template_preprocess_*() calls with service method calls.
 *
 * Drupal 11.2.0 deprecated the global template_preprocess_* functions for
 * block, container, html, links, page, time, datetime_form, and
 * datetime_wrapper. Each deprecated function now delegates to a method on a
 * core service.
 */
final class DeprecatedTemplatePreprocesCallsRector extends AbstractRector
{
    /**
     * Maps each deprecated function name to [FQCN, methodName].
     */
    private const MAP = [
        'template_preprocess_block'            => ['Drupal\\block\\Hook\\BlockHooks', 'preprocessBlock'],
        'template_preprocess_container'        => ['Drupal\\Core\\Theme\\ThemePreprocess', 'preprocessContainer'],
        'template_preprocess_html'             => ['Drupal\\Core\\Theme\\ThemePreprocess', 'preprocessHtml'],
        'template_preprocess_links'            => ['Drupal\\Core\\Theme\\ThemePreprocess', 'preprocessLinks'],
        'template_preprocess_page'             => ['Drupal\\Core\\Theme\\ThemePreprocess', 'preprocessPage'],
        'template_preprocess_time'             => ['Drupal\\Core\\Datetime\\DatePreprocess', 'preprocessTime'],
        'template_preprocess_datetime_form'    => ['Drupal\\Core\\Datetime\\DatePreprocess', 'preprocessDatetimeForm'],
        'template_preprocess_datetime_wrapper' => ['Drupal\\Core\\Datetime\\DatePreprocess', 'preprocessDatetimeWrapper'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated template_preprocess_*() calls with the corresponding service method calls (Drupal 11.2.0).',
            [new CodeSample(
                'template_preprocess_block($variables);',
                '\\Drupal::service(\\Drupal\\block\\Hook\\BlockHooks::class)->preprocessBlock($variables);',
            )],
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
        if ($name === null || !isset(self::MAP[$name])) {
            return null;
        }

        [$fqcn, $method] = self::MAP[$name];

        // \Drupal::service(ClassName::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            new Identifier('service'),
            [new Arg(new ClassConstFetch(
                new FullyQualified($fqcn),
                'class',
            ))],
        );

        return new MethodCall(
            $serviceCall,
            new Identifier($method),
            $node->args,
        );
    }
}
