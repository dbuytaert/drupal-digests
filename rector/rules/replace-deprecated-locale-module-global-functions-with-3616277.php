<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates several locale.module global functions in favor
 * of methods on new classes. locale_string_is_safe() becomes the static
 * LocaleXss::stringIsSafe(); locale_is_translatable(),
 * locale_translatable_language_list(), and locale_js_translate() become
 * instance methods on the LocaleLanguages and LocaleJs services,
 * obtained via \Drupal::service(). This rule rewrites direct calls to
 * the old global functions so contrib and custom modules keep working
 * after the functions are removed in Drupal 13.
 *
 * Before:
 *   $safe = locale_string_is_safe($string);
 *   $translatable = locale_is_translatable($langcode);
 *   $languages = locale_translatable_language_list();
 *   $translation_file = locale_js_translate($files, $language_interface);
 *
 * After:
 *   $safe = \Drupal\locale\LocaleXss::stringIsSafe($string);
 *   $translatable = \Drupal::service(\Drupal\locale\LocaleLanguages::class)->isTranslatable($langcode);
 *   $languages = \Drupal::service(\Drupal\locale\LocaleLanguages::class)->getTranslatableLanguages();
 *   $translation_file = \Drupal::service(\Drupal\locale\LocaleJs::class)->jsTranslate($files, $language_interface);
 *
 * Caveats:
 *   Only covers the four functions with a direct 1:1 replacement
 *   (locale_string_is_safe, locale_is_translatable,
 *   locale_translatable_language_list, locale_js_translate).
 *   locale_translation_use_remote_source() and
 *   locale_translation_language_table() are also deprecated by this
 *   issue but have no direct replacement (inlined config check, or a
 *   class method meant to be referenced by name as a #after_build
 *   callback), so they are intentionally left untouched; callers of
 *   those two must be migrated by hand.
 *
 * @see https://www.drupal.org/node/3616277
 * @deprecated drupal:11.5.0
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

final class ReplaceDeprecatedLocaleFunctionsRector extends AbstractRector
{
    /**
     * Functions replaced by a static method call.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const STATIC_MAP = [
        'locale_string_is_safe' => ['Drupal\locale\LocaleXss', 'stringIsSafe'],
    ];

    /**
     * Functions replaced by a call on a service obtained via \Drupal::service().
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const SERVICE_MAP = [
        'locale_is_translatable' => ['Drupal\locale\LocaleLanguages', 'isTranslatable'],
        'locale_translatable_language_list' => ['Drupal\locale\LocaleLanguages', 'getTranslatableLanguages'],
        'locale_js_translate' => ['Drupal\locale\LocaleJs', 'jsTranslate'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale.module global functions with calls on their replacement classes.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$safe = locale_string_is_safe($string);
$translatable = locale_is_translatable($langcode);
$languages = locale_translatable_language_list();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$safe = \Drupal\locale\LocaleXss::stringIsSafe($string);
$translatable = \Drupal::service(\Drupal\locale\LocaleLanguages::class)->isTranslatable($langcode);
$languages = \Drupal::service(\Drupal\locale\LocaleLanguages::class)->getTranslatableLanguages();
CODE_SAMPLE
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

        $functionName = $this->getName($node->name);
        if ($functionName === null) {
            return null;
        }

        if (isset(self::STATIC_MAP[$functionName])) {
            [$class, $method] = self::STATIC_MAP[$functionName];
            return new StaticCall(new FullyQualified($class), $method, $node->args);
        }

        if (isset(self::SERVICE_MAP[$functionName])) {
            [$class, $method] = self::SERVICE_MAP[$functionName];
            $service = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new ClassConstFetch(new FullyQualified($class), 'class'))],
            );
            return new MethodCall($service, $method, $node->args);
        }

        return null;
    }
}
