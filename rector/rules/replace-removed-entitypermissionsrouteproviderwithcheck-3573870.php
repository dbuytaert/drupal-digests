<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3573870
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces all references to the removed
// EntityPermissionsRouteProviderWithCheck class with its replacement
// EntityPermissionsRouteProvider. The WithCheck variant added a custom
// access check via EntityPermissionsForm::access(), which was also
// deprecated. Both were removed in drupal:12.0.0 after being deprecated
// in drupal:11.1.0 (issue #3573870). The permission check is now done
// via a _permission requirement on the route itself.
//
// Before:
//   use Drupal\user\Entity\EntityPermissionsRouteProviderWithCheck;
//   
//   class MyRouteProvider extends EntityPermissionsRouteProviderWithCheck {
//   }
//   
//   // In entity type definitions:
//   'route_provider' => [
//     'permissions' => EntityPermissionsRouteProviderWithCheck::class,
//   ],
//
// After:
//   use Drupal\user\Entity\EntityPermissionsRouteProvider;
//   
//   class MyRouteProvider extends \Drupal\user\Entity\EntityPermissionsRouteProvider {
//   }
//   
//   // In entity type definitions:
//   'route_provider' => [
//     'permissions' => \Drupal\user\Entity\EntityPermissionsRouteProvider::class,
//   ],


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * Replaces the removed EntityPermissionsRouteProviderWithCheck class with
 * EntityPermissionsRouteProvider.
 *
 * EntityPermissionsRouteProviderWithCheck was deprecated in drupal:11.1.0
 * and removed in drupal:12.0.0 (issue #3573870). Use
 * EntityPermissionsRouteProvider instead. The custom access check it provided
 * via EntityPermissionsForm::access() was also deprecated and removed; the
 * replacement EntityPermissionsRouteProvider uses a _permission requirement
 * ('administer permissions') on the route definition instead.
 *
 * @see https://www.drupal.org/node/3384745
 * @see https://www.drupal.org/project/drupal/issues/3573870
 */
