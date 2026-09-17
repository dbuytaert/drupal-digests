<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal core moved the shortcuts navigation block plugin class out of
 * the Navigation module and into the Shortcut module.
 * Drupal\navigation\Plugin\Block\NavigationShortcutsBlock is deprecated
 * in drupal:11.5.0 and removed in drupal:12.0.0 in favor of
 * Drupal\shortcut\Plugin\Block\ShortcutNavigationBlock, which has the
 * same constructor signature. This rule rewrites use, extends,
 * instanceof, new, and type-hint references from the old class to the
 * new one.
 *
 * Before:
 *   use Drupal\navigation\Plugin\Block\NavigationShortcutsBlock;
 *   
 *   class MyCustomBlock extends NavigationShortcutsBlock {
 *   
 *   }
 *
 * After:
 *   use Drupal\shortcut\Plugin\Block\ShortcutNavigationBlock;
 *   
 *   class MyCustomBlock extends ShortcutNavigationBlock {
 *   
 *   }
 *
 * Caveats:
 *   Does not cover the related internal rename of
 *   Drupal\navigation\ShortcutLazyBuilder to
 *   Drupal\shortcut\ShortcutNavigationLazyBuilder, since that class
 *   carries no @deprecated marker or back-compat shim in core (it is
 *   @internal and was removed outright), so there is no documented
 *   deprecation to target.
 *
 * @see https://www.drupal.org/node/3581816
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\navigation\Plugin\Block\NavigationShortcutsBlock' => 'Drupal\shortcut\Plugin\Block\ShortcutNavigationBlock',
    ]);
