<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3572339
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites locale_translation_batch_update_build() to
// \Drupal::service(LocaleFetch::class)->batchUpdateBuild() and
// locale_translation_batch_fetch_build() to ->batchFetchBuild(). Both
// functions were deprecated in drupal:11.4.0 and removed in
// drupal:13.0.0 (issue #3572339). The third function
// _locale_translation_fetch_operations() has no replacement and cannot
// be auto-migrated.
//
// Before:
//   locale_translation_batch_update_build($projects, $langcodes, $options);
//   locale_translation_batch_fetch_build($projects, $langcodes, $options);
//
// After:
//   \Drupal::service(\Drupal\locale\LocaleFetch::class)->batchUpdateBuild($projects, $langcodes, $options);
//   \Drupal::service(\Drupal\locale\LocaleFetch::class)->batchFetchBuild($projects, $langcodes, $options);


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
 * Replaces deprecated locale_translation_batch_update_build() and
 * locale_translation_batch_fetch_build() with LocaleFetch service calls.
 *
 * Both procedural functions were deprecated in drupal:11.4.0 and are removed
 * in drupal:13.0.0 (issue #3572339). The replacement is
 * \Drupal::service(\Drupal\locale\LocaleFetch::class)->batchUpdateBuild() and
 * ->batchFetchBuild() respectively. All arguments are preserved.
 *
 * _locale_translation_fetch_operations() has no replacement and cannot be
 * automatically migrated.
 *
 * @see https://www.drupal.org/node/3572345
 * @see https://www.drupal.org/project/drupal/issues/3572339
 */
final class ReplaceLocaleFetchFunctionsRector extends AbstractRector
{
    private const LOCALE_FETCH_CLASS = 'Drupal\\locale\\LocaleFetch';

    /** Map: deprecated function name => replacement method name */
    private const FUNC_METHOD_MAP = [
        'locale_translation_batch_update_build' => 'batchUpdateBuild',
        'locale_translation_batch_fetch_build'  => 'batchFetchBuild',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale_translation_batch_update_build() and locale_translation_batch_fetch_build() with \\Drupal::service(LocaleFetch::class) method calls',
            [
                new CodeSample(
                    'locale_translation_batch_update_build($projects, $langcodes, $options);',
                    '\\Drupal::service(\\Drupal\\locale\\LocaleFetch::class)->batchUpdateBuild($projects, $langcodes, $options);'
                ),
                new CodeSample(
                    'locale_translation_batch_fetch_build($projects, $langcodes, $options);',
                    '\\Drupal::service(\\Drupal\\locale\\LocaleFetch::class)->batchFetchBuild($projects, $langcodes, $options);'
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

        if (!isset(self::FUNC_METHOD_MAP[$funcName])) {
            return null;
        }

        $methodName = self::FUNC_METHOD_MAP[$funcName];

        // Build: \Drupal::service(\Drupal\locale\LocaleFetch::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified(self::LOCALE_FETCH_CLASS),
                'class'
            ))]
        );

        // Build: ->batchUpdateBuild(...) or ->batchFetchBuild(...)
        return new MethodCall(
            $serviceCall,
            $methodName,
            $node->args
        );
    }
}
