<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces 17 deprecated procedural functions from locale.batch.inc,
 * locale.bulk.inc, and locale.compare.inc with equivalent calls on the
 * new LocaleFetch, LocaleImportBatch, LocaleConfigBatch, and
 * LocaleProjectChecker services. All deprecated functions delegate
 * directly to the service methods with identical arguments, making the
 * rewrite safe and mechanical.
 *
 * Before:
 *   locale_translate_batch_build($files, $options);
 *   locale_config_batch_update_components($options, $langcodes);
 *   locale_translation_batch_fetch_finished($success, $results);
 *
 * After:
 *   \Drupal::service(\Drupal\locale\LocaleImportBatch::class)->buildBatch($files, $options);
 *   \Drupal::service(\Drupal\locale\LocaleConfigBatch::class)->buildBatch($options, $langcodes);
 *   \Drupal::service(\Drupal\locale\LocaleFetch::class)->batchFinished($success, $results);
 *
 * Caveats:
 *   locale_config_batch_build and locale_translation_batch_status_build
 *   are not rewritten: the former changed its argument signature and
 *   the latter changed its behavior (the replacement calls batch_set()
 *   immediately rather than returning the array). Those two functions
 *   require manual migration.
 *
 * @see https://www.drupal.org/node/3581303
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceLocaleBatchProceduralFunctionsRector extends AbstractRector
{
    // Map: function_name => [ServiceClass FQCN, method name]
    private const FUNCTION_MAP = [
        'locale_translation_batch_version_check'           => ['Drupal\\locale\\LocaleFetch', 'batchVersionCheck'],
        'locale_translation_batch_status_check'            => ['Drupal\\locale\\LocaleFetch', 'batchStatusCheck'],
        'locale_translation_batch_fetch_download'          => ['Drupal\\locale\\LocaleFetch', 'batchDownload'],
        'locale_translation_batch_fetch_import'            => ['Drupal\\locale\\LocaleFetch', 'batchImport'],
        'locale_translation_batch_fetch_finished'          => ['Drupal\\locale\\LocaleFetch', 'batchFinished'],
        '_locale_translation_batch_status_operations'      => ['Drupal\\locale\\LocaleFetch', 'getStatusOperations'],
        'locale_translate_batch_build'                     => ['Drupal\\locale\\LocaleImportBatch', 'buildBatch'],
        'locale_translate_batch_import'                    => ['Drupal\\locale\\LocaleImportBatch', 'batchImport'],
        'locale_translate_batch_import_save'               => ['Drupal\\locale\\LocaleImportBatch', 'batchSave'],
        'locale_translate_batch_refresh'                   => ['Drupal\\locale\\LocaleImportBatch', 'batchRefresh'],
        'locale_translate_batch_finished'                  => ['Drupal\\locale\\LocaleImportBatch', 'batchFinished'],
        'locale_config_batch_update_components'            => ['Drupal\\locale\\LocaleConfigBatch', 'buildBatch'],
        'locale_config_batch_update_default_config_langcodes' => ['Drupal\\locale\\LocaleConfigBatch', 'batchUpdateDefaultConfigLangcodes'],
        'locale_config_batch_update_config_translations'   => ['Drupal\\locale\\LocaleConfigBatch', 'batchUpdateConfigTranslations'],
        'locale_config_batch_finished'                     => ['Drupal\\locale\\LocaleConfigBatch', 'batchFinished'],
        'locale_translation_check_projects_batch'          => ['Drupal\\locale\\LocaleProjectChecker', 'triggerBatch'],
        'locale_translation_batch_status_finished'         => ['Drupal\\locale\\LocaleProjectChecker', 'batchFinished'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale batch procedural functions with service method calls.',
            [new CodeSample(
                'locale_translate_batch_build($files, $options);',
                '\\Drupal::service(\\Drupal\\locale\\LocaleImportBatch::class)->buildBatch($files, $options);',
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
        if (!$node instanceof FuncCall) {
            return null;
        }
        $funcName = $this->getName($node->name);
        if ($funcName === null || !isset(self::FUNCTION_MAP[$funcName])) {
            return null;
        }
        [$serviceClass, $method] = self::FUNCTION_MAP[$funcName];

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified($serviceClass), 'class'))],
        );
        return new MethodCall($serviceCall, $method, $node->args);
    }
}
