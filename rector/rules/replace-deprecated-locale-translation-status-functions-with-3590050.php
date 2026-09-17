<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.4 deprecated five global locale translation status functions
 * in favour of the LocaleSource service. This rule rewrites the four
 * functions whose arguments map 1:1 to the new service methods:
 * locale_translation_get_status() → loadSources(),
 * locale_translation_status_save() → saveSource(),
 * locale_translation_status_delete_projects() → deleteSources(), and
 * locale_translation_clear_status() → clearSources(). All are removed in
 * Drupal 13.0.
 *
 * Before:
 *   locale_translation_get_status($projects, $langcodes);
 *   locale_translation_status_save($project, $langcode, $type, $file);
 *   locale_translation_status_delete_projects($list);
 *   locale_translation_clear_status();
 *
 * After:
 *   \Drupal::service(\Drupal\locale\LocaleSource::class)->loadSources($projects, $langcodes);
 *   \Drupal::service(\Drupal\locale\LocaleSource::class)->saveSource($project, $langcode, $type, $file);
 *   \Drupal::service(\Drupal\locale\LocaleSource::class)->deleteSources($list);
 *   \Drupal::service(\Drupal\locale\LocaleSource::class)->clearSources();
 *
 * Caveats:
 *   locale_translation_status_delete_languages(array $langcodes) is not
 *   rewritten: its replacement deleteSourcesByLanguage(string
 *   $langcode) takes a single language code, so the call site must be
 *   manually converted to a foreach loop.
 *
 * @see https://www.drupal.org/node/3590050
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

final class DeprecatedLocaleStatusFunctionsRector extends AbstractRector
{
    // Maps deprecated global function names to their replacement method names
    // on \Drupal\locale\LocaleSource (all pass arguments through unchanged).
    private const array REPLACEMENTS = [
        'locale_translation_get_status'          => 'loadSources',
        'locale_translation_status_save'         => 'saveSource',
        'locale_translation_status_delete_projects' => 'deleteSources',
        'locale_translation_clear_status'        => 'clearSources',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale translation status functions with LocaleSource service methods.',
            [new CodeSample(
                'locale_translation_get_status($projects, $langcodes);',
                '\\Drupal::service(\\Drupal\\locale\\LocaleSource::class)->loadSources($projects, $langcodes);',
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
        if ($funcName === null || !array_key_exists($funcName, self::REPLACEMENTS)) {
            return null;
        }
        $methodName = self::REPLACEMENTS[$funcName];

        // Build \Drupal::service(\Drupal\locale\LocaleSource::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified('Drupal\\locale\\LocaleSource'),
                'class',
            ))],
        );

        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
