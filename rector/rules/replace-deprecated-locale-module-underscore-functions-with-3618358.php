<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the remaining _locale_* underscore helper
 * functions in locale.module in favor of methods on the
 * Drupal\locale\LocaleJs service. This rule rewrites direct calls to
 * _locale_refresh_translations(), _locale_invalidate_js(),
 * _locale_parse_js_file(), and _locale_rebuild_js() into the equivalent
 * \Drupal::service(\Drupal\locale\LocaleJs::class)->method() call,
 * preserving arguments unchanged. Contrib code that rebuilds or
 * invalidates JavaScript translation files keeps working after these
 * globals are removed in Drupal 13.0.0.
 *
 * Before:
 *   _locale_invalidate_js($langcode);
 *   _locale_rebuild_js($langcode);
 *   _locale_parse_js_file($filepath);
 *   _locale_refresh_translations($langcodes, $lids);
 *
 * After:
 *   \Drupal::service(\Drupal\locale\LocaleJs::class)->invalidate($langcode);
 *   \Drupal::service(\Drupal\locale\LocaleJs::class)->rebuild($langcode);
 *   \Drupal::service(\Drupal\locale\LocaleJs::class)->parseJsFile($filepath);
 *   \Drupal::service(\Drupal\locale\LocaleJs::class)->refreshTranslations($langcodes, $lids);
 *
 * Caveats:
 *   _locale_refresh_configuration() and _locale_strip_quotes() are also
 *   deprecated by this issue but have no replacement API, so calls to
 *   them are intentionally left untouched; callers must be manually
 *   inlined or removed. LocaleJs::parseJsFile() and LocaleJs::rebuild()
 *   are marked @internal in core (public only for test coverage), so
 *   rewritten call sites still depend on an internal API surface that
 *   core may change without a deprecation cycle.
 *
 * @see https://www.drupal.org/node/3618358
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceLocaleJsUnderscoreFunctionsRector extends AbstractRector
{
    /**
     * @var array<string, string>
     */
    private const METHOD_MAP = [
        '_locale_refresh_translations' => 'refreshTranslations',
        '_locale_invalidate_js' => 'invalidate',
        '_locale_parse_js_file' => 'parseJsFile',
        '_locale_rebuild_js' => 'rebuild',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale.module underscore functions with Drupal\locale\LocaleJs service calls.',
            [new CodeSample(
                '_locale_invalidate_js($langcode);',
                "\\Drupal::service(\\Drupal\\locale\\LocaleJs::class)->invalidate(\$langcode);",
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
        if (!$node->name instanceof Name) {
            // Skip dynamic calls, e.g. $fn(...).
            return null;
        }
        $functionName = $this->getName($node->name);
        if ($functionName === null || !isset(self::METHOD_MAP[$functionName])) {
            return null;
        }

        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified('Drupal\\locale\\LocaleJs'), 'class'))],
        );

        return new MethodCall($service, self::METHOD_MAP[$functionName], $node->args);
    }
}
