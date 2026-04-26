<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566792
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to the deprecated
// ckeditor5_filter_format_edit_form_submit() and
// _update_ckeditor5_html_filter() procedural functions to equivalent
// \Drupal::service(Ckeditor5Hooks::class)->method() calls. Both
// functions were deprecated in drupal:11.4.0 and removed in
// drupal:12.0.0 as part of eliminating procedural hook code from
// ckeditor5.module. The third deprecated function _ckeditor5_theme_css()
// has no provided replacement and is excluded.
//
// Before:
//   ckeditor5_filter_format_edit_form_submit($form, $form_state);
//   _update_ckeditor5_html_filter($form, $form_state);
//
// After:
//   \Drupal::service(\Drupal\ckeditor5\Hook\Ckeditor5Hooks::class)->filterFormatEditFormSubmit($form, $form_state);
//   \Drupal::service(\Drupal\ckeditor5\Hook\Ckeditor5Hooks::class)->updateCkeditor5HtmlFilter($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated ckeditor5 procedural functions with Ckeditor5Hooks
 * service method calls.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:12.0.0.
 *
 * @see https://www.drupal.org/node/3566774
 * @see https://www.drupal.org/project/drupal/issues/3566792
 */
final class ReplaceCkeditor5ProceduralFunctionsRector extends AbstractRector
{
    /**
     * Map of deprecated function name => new Ckeditor5Hooks method name.
     *
     * _ckeditor5_theme_css() is intentionally excluded: its deprecation
     * notice states "No replacement is provided", so it cannot be
     * automatically rewritten.
     */
    private const FUNCTION_MAP = [
        'ckeditor5_filter_format_edit_form_submit' => 'filterFormatEditFormSubmit',
        '_update_ckeditor5_html_filter'            => 'updateCkeditor5HtmlFilter',
    ];

    private const HOOKS_CLASS = 'Drupal\\ckeditor5\\Hook\\Ckeditor5Hooks';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated ckeditor5_filter_format_edit_form_submit() and _update_ckeditor5_html_filter() procedural functions with equivalent Ckeditor5Hooks service method calls.',
            [
                new CodeSample(
                    'ckeditor5_filter_format_edit_form_submit($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\ckeditor5\\Hook\\Ckeditor5Hooks::class)->filterFormatEditFormSubmit($form, $form_state);'
                ),
                new CodeSample(
                    '_update_ckeditor5_html_filter($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\ckeditor5\\Hook\\Ckeditor5Hooks::class)->updateCkeditor5HtmlFilter($form, $form_state);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }

        $functionName = $this->getName($node->name);
        if ($functionName === null || !array_key_exists($functionName, self::FUNCTION_MAP)) {
            return null;
        }

        $methodName = self::FUNCTION_MAP[$functionName];

        // Build: \Drupal::service(\Drupal\ckeditor5\Hook\Ckeditor5Hooks::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [
                new Arg(
                    new ClassConstFetch(
                        new FullyQualified(self::HOOKS_CLASS),
                        'class'
                    )
                ),
            ]
        );

        // Build: ->filterFormatEditFormSubmit(...$originalArgs)
        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
