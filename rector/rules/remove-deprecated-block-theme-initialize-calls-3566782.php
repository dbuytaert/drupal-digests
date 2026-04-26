<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566782
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to block_theme_initialize(), deprecated in drupal:11.4.0
// and removed in drupal:13.0.0 with no replacement. The underlying logic
// was moved to the protected BlockHooks::themeInitialize() method and is
// now invoked internally by Drupal's hook system. External callers must
// simply drop the call.
//
// Before:
//   function my_module_themes_installed(array $theme_list): void {
//     foreach ($theme_list as $theme) {
//       block_theme_initialize($theme);
//     }
//   }
//
// After:
//   function my_module_themes_installed(array $theme_list): void {
//     foreach ($theme_list as $theme) {
//     }
//   }


use Rector\Config\RectorConfig;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;

/**
 * Removes calls to the deprecated block_theme_initialize() function.
 *
 * block_theme_initialize() is deprecated in drupal:11.4.0 and removed in
 * drupal:13.0.0. No replacement is provided. The underlying logic has been
 * moved to the protected method BlockHooks::themeInitialize(), which Drupal
 * now calls internally through its hook system. Any custom or contrib code
 * that called block_theme_initialize() should simply remove the call.
 *
 * @see https://www.drupal.org/node/3566783
 * @see https://www.drupal.org/project/drupal/issues/3566782
 */
