<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3573896
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to theme_get_setting($setting_name, $theme) to \Drupal:
// :service(ThemeSettingsProvider::class)->getSetting($setting_name,
// $theme), and replaces _system_default_theme_features() with the class
// constant ThemeSettingsProvider::DEFAULT_THEME_FEATURES. Both functions
// were deprecated in drupal:11.3.0 and removed in drupal:13.0.0 (issue
// #3573896). All arguments are preserved.
//
// Before:
//   $logo = theme_get_setting('logo.url');
//   $fav = theme_get_setting('favicon.url', 'claro');
//   $features = _system_default_theme_features();
//
// After:
//   $logo = \Drupal::service(\Drupal\Core\Extension\ThemeSettingsProvider::class)->getSetting('logo.url');
//   $fav = \Drupal::service(\Drupal\Core\Extension\ThemeSettingsProvider::class)->getSetting('favicon.url', 'claro');
//   $features = \Drupal\Core\Extension\ThemeSettingsProvider::DEFAULT_THEME_FEATURES;


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
 * Replaces deprecated theme_get_setting() and _system_default_theme_features()
 * with their ThemeSettingsProvider equivalents.
 *
 * theme_get_setting($setting_name, $theme = NULL) was deprecated in
 * drupal:11.3.0 and is removed in drupal:13.0.0 (issue #3573896). Use
 * \Drupal::service(ThemeSettingsProvider::class)->getSetting() instead.
 *
 * _system_default_theme_features() was also deprecated in drupal:11.3.0 and
 * is removed in drupal:13.0.0. Use
 * ThemeSettingsProvider::DEFAULT_THEME_FEATURES instead.
 */
final class ReplaceThemeGetSettingRector extends AbstractRector
{
    private const THEME_SETTINGS_PROVIDER = 'Drupal\\Core\\Extension\\ThemeSettingsProvider';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated theme_get_setting() and _system_default_theme_features() with ThemeSettingsProvider equivalents',
            [
                new CodeSample(
                    "theme_get_setting('logo.url');",
                    "\\Drupal::service(\\Drupal\\Core\\Extension\\ThemeSettingsProvider::class)->getSetting('logo.url');"
                ),
                new CodeSample(
                    '_system_default_theme_features();',
                    '\\Drupal\\Core\\Extension\\ThemeSettingsProvider::DEFAULT_THEME_FEATURES;'
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

        if ($funcName === 'theme_get_setting') {
            return $this->refactorThemeGetSetting($node);
        }

        if ($funcName === '_system_default_theme_features') {
            // _system_default_theme_features() => ThemeSettingsProvider::DEFAULT_THEME_FEATURES
            return new ClassConstFetch(
                new FullyQualified(self::THEME_SETTINGS_PROVIDER),
                'DEFAULT_THEME_FEATURES'
            );
        }

        return null;
    }

    private function refactorThemeGetSetting(FuncCall $node): Node
    {
        // Build: \Drupal::service(ThemeSettingsProvider::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified(self::THEME_SETTINGS_PROVIDER),
                'class'
            ))]
        );

        // Build: ->getSetting($setting_name[, $theme])
        return new MethodCall(
            $serviceCall,
            'getSetting',
            $node->args
        );
    }
}
