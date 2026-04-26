<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571593
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites \Drupal::config('locale.settings')->get('translation.path')
// (and the configFactory()->get(...) variant) to
// \Drupal\Core\Site\Settings::get('locale_translation_path',
// 'public://translations'). The translation.path config key was
// deprecated in drupal:11.4.0 and is removed in drupal:13.0.0 (issue
// #3571593); the path must now be set as
// $settings['locale_translation_path'] in settings.php.
//
// Before:
//   \Drupal::config('locale.settings')->get('translation.path');
//
// After:
//   \Drupal\Core\Site\Settings::get('locale_translation_path', 'public://translations');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated locale.settings:translation.path config reads with Settings::get().
 *
 * The configuration key locale.settings:translation.path was deprecated in
 * drupal:11.4.0 and is removed in drupal:13.0.0 (issue #3571593). The
 * correct replacement is \Drupal\Core\Site\Settings::get('locale_translation_path',
 * 'public://translations').
 *
 * This rule rewrites the direct chained-call pattern:
 *   \Drupal::config('locale.settings')->get('translation.path')
 * and:
 *   \Drupal::configFactory()->get('locale.settings')->get('translation.path')
 * and also the $this->config() and $configFactory->get() variants common in
 * Drupal plugins, controllers, and form classes.
 *
 * @see https://www.drupal.org/node/3571594
 * @see https://www.drupal.org/project/drupal/issues/3571593
 */
final class ReplaceLocaleTranslationPathConfigRector extends AbstractRector
{
    private const SETTINGS_CLASS   = 'Drupal\\Core\\Site\\Settings';
    private const SETTINGS_KEY     = 'locale_translation_path';
    private const SETTINGS_DEFAULT = 'public://translations';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated \\Drupal::config('locale.settings')->get('translation.path') with Settings::get('locale_translation_path')",
            [
                new CodeSample(
                    "\\Drupal::config('locale.settings')->get('translation.path');",
                    "\\Drupal\\Core\\Site\\Settings::get('locale_translation_path', 'public://translations');"
                ),
                new CodeSample(
                    "\\Drupal::configFactory()->get('locale.settings')->get('translation.path');",
                    "\\Drupal\\Core\\Site\\Settings::get('locale_translation_path', 'public://translations');"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        // Outer call must be ->get('translation.path')
        if (!$this->isName($node->name, 'get')) {
            return null;
        }

        if (count($node->args) < 1) {
            return null;
        }

        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }

        if (!$firstArg->value instanceof String_) {
            return null;
        }

        if ($firstArg->value->value !== 'translation.path') {
            return null;
        }

        // The caller must be a config object loaded from 'locale.settings'.
        if (!$this->isLocaleSettingsConfigCall($node->var)) {
            return null;
        }

        // Build: \Drupal\Core\Site\Settings::get('locale_translation_path', 'public://translations')
        return new StaticCall(
            new FullyQualified(self::SETTINGS_CLASS),
            new Identifier('get'),
            [
                new Arg(new String_(self::SETTINGS_KEY)),
                new Arg(new String_(self::SETTINGS_DEFAULT)),
            ]
        );
    }

    /**
     * Returns true when $expr is one of the accepted forms for retrieving
     * the locale.settings config object:
     *
     *   \Drupal::config('locale.settings')
     *   \Drupal::configFactory()->get('locale.settings')
     *   $this->config('locale.settings')            (ControllerBase / FormBase)
     *   $this->configFactory->get('locale.settings')
     *   $anyExpr->get('locale.settings')
     */
    private function isLocaleSettingsConfigCall(Node $expr): bool
    {
        if (!$expr instanceof MethodCall && !$expr instanceof StaticCall) {
            return false;
        }

        $methodName = $expr->name instanceof Identifier
            ? $expr->name->toString()
            : null;

        if (!in_array($methodName, ['config', 'get'], true)) {
            return false;
        }

        if (empty($expr->args)) {
            return false;
        }

        $firstArg = $expr->args[0];
        if (!$firstArg instanceof Arg) {
            return false;
        }

        if (!$firstArg->value instanceof String_) {
            return false;
        }

        return $firstArg->value->value === 'locale.settings';
    }
}
