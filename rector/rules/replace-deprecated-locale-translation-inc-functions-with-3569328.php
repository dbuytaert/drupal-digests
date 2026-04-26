<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3569328
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites seven procedural functions from locale.translation.inc
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0 (issue
// #3569328). locale_translation_get_projects() and
// locale_translation_clear_cache_projects() become calls on
// \Drupal::service('locale.project'). Five LocaleSource-backed functions
// (locale_translation_load_sources, locale_translation_build_sources,
// locale_translation_source_check_file, locale_translation_source_build,
// locale_translation_build_server_pattern) become calls on
// \Drupal::service(LocaleSource::class). locale_cron_fill_queue() is
// removed with no replacement.
//
// Before:
//   $projects = locale_translation_get_projects(['mymodule']);
//   locale_translation_clear_cache_projects();
//   $sources = locale_translation_load_sources($projects, $langcodes);
//   $built = locale_translation_build_sources($projects, $langcodes);
//   locale_translation_source_check_file($source);
//   $obj = locale_translation_source_build($project, 'fr', 'mymodule.po');
//   $pattern = locale_translation_build_server_pattern($project, '%project-%version.%language.po');
//   locale_cron_fill_queue();
//
// After:
//   $projects = \Drupal::service('locale.project')->getProjects(['mymodule']);
//   \Drupal::service('locale.project')->resetCache();
//   $sources = \Drupal::service(\Drupal\locale\LocaleSource::class)->loadSources($projects, $langcodes);
//   $built = \Drupal::service(\Drupal\locale\LocaleSource::class)->buildSources($projects, $langcodes);
//   \Drupal::service(\Drupal\locale\LocaleSource::class)->sourceCheckFile($source);
//   $obj = \Drupal::service(\Drupal\locale\LocaleSource::class)->sourceBuild($project, 'fr', 'mymodule.po');
//   $pattern = \Drupal::service(\Drupal\locale\LocaleSource::class)->buildServerPattern($project, '%project-%version.%language.po');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated procedural functions from locale.translation.inc with
 * their service-based equivalents.
 *
 * Deprecated in drupal:11.4.0 and removed in drupal:13.0.0:
 *
 *   locale_translation_get_projects($names)
 *     => \Drupal::service('locale.project')->getProjects($names)
 *   locale_translation_clear_cache_projects()
 *     => \Drupal::service('locale.project')->resetCache()
 *   locale_translation_load_sources($projects, $langcodes)
 *     => \Drupal::service(\Drupal\locale\LocaleSource::class)->loadSources(...)
 *   locale_translation_build_sources($projects, $langcodes)
 *     => \Drupal::service(\Drupal\locale\LocaleSource::class)->buildSources(...)
 *   locale_translation_source_check_file($source)
 *     => \Drupal::service(\Drupal\locale\LocaleSource::class)->sourceCheckFile($source)
 *   locale_translation_source_build($project, $langcode, $filename)
 *     => \Drupal::service(\Drupal\locale\LocaleSource::class)->sourceBuild(...)
 *   locale_translation_build_server_pattern($project, $template)
 *     => \Drupal::service(\Drupal\locale\LocaleSource::class)->buildServerPattern(...)
 *
 * No replacement (call removed by RemoveFuncCallRector):
 *   locale_cron_fill_queue()
 *
 * @see https://www.drupal.org/node/3569330
 * @see https://www.drupal.org/project/drupal/issues/3569328
 */
final class ReplaceLocaleTranslationIncFunctionsRector extends AbstractRector
{
    private const LOCALE_SOURCE_CLASS = 'Drupal\\locale\\LocaleSource';
    private const LOCALE_PROJECT_SERVICE = 'locale.project';

    /**
     * Functions whose calls should be rewritten to
     * \Drupal::service(LocaleSource::class)->method().
     */
    private const LOCALE_SOURCE_MAP = [
        'locale_translation_load_sources'         => 'loadSources',
        'locale_translation_build_sources'        => 'buildSources',
        'locale_translation_source_check_file'    => 'sourceCheckFile',
        'locale_translation_source_build'         => 'sourceBuild',
        'locale_translation_build_server_pattern' => 'buildServerPattern',
    ];

    /**
     * Functions whose calls should be rewritten to
     * \Drupal::service('locale.project')->method().
     */
    private const LOCALE_PROJECT_MAP = [
        'locale_translation_get_projects'         => 'getProjects',
        'locale_translation_clear_cache_projects' => 'resetCache',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale.translation.inc procedural functions with service calls (removed in drupal:13.0.0)',
            [
                new CodeSample(
                    '$projects = locale_translation_get_projects([\'mymodule\']);',
                    '$projects = \\Drupal::service(\'locale.project\')->getProjects([\'mymodule\']);'
                ),
                new CodeSample(
                    'locale_translation_clear_cache_projects();',
                    '\\Drupal::service(\'locale.project\')->resetCache();'
                ),
                new CodeSample(
                    '$sources = locale_translation_load_sources($projects, $langcodes);',
                    '$sources = \\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->loadSources($projects, $langcodes);'
                ),
                new CodeSample(
                    '$built = locale_translation_build_sources($projects, $langcodes);',
                    '$built = \\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->buildSources($projects, $langcodes);'
                ),
                new CodeSample(
                    'locale_translation_source_check_file($source);',
                    '\\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->sourceCheckFile($source);'
                ),
                new CodeSample(
                    '$obj = locale_translation_source_build($project, \'fr\', \'mymodule.po\');',
                    '$obj = \\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->sourceBuild($project, \'fr\', \'mymodule.po\');'
                ),
                new CodeSample(
                    '$pattern = locale_translation_build_server_pattern($project, \'%project-%version.%language.po\');',
                    '$pattern = \\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->buildServerPattern($project, \'%project-%version.%language.po\');'
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

        // LocaleSource service replacements.
        if (isset(self::LOCALE_SOURCE_MAP[$funcName])) {
            $serviceCall = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new ClassConstFetch(
                    new FullyQualified(self::LOCALE_SOURCE_CLASS),
                    'class'
                ))]
            );

            return new MethodCall($serviceCall, self::LOCALE_SOURCE_MAP[$funcName], $node->args);
        }

        // locale.project service replacements.
        if (isset(self::LOCALE_PROJECT_MAP[$funcName])) {
            $serviceCall = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new String_(self::LOCALE_PROJECT_SERVICE))]
            );

            return new MethodCall($serviceCall, self::LOCALE_PROJECT_MAP[$funcName], $node->args);
        }

        return null;
    }
}
