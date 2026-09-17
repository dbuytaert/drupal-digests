<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal\user\Plugin\Search\UserSearch was moved into a new search_user
 * submodule and renamed to Drupal\search_user\Plugin\Search\SearchUser,
 * deprecated in drupal:11.5.0 and removed in drupal:12.0.0. The
 * constructor signature is unchanged, so this rule uses Rector's built-
 * in RenameClassRector to rewrite use, extends, instanceof, and new
 * references from the old class to the new one across contrib and custom
 * search plugins.
 *
 * Before:
 *   use Drupal\user\Plugin\Search\UserSearch;
 *   
 *   class MyPlugin extends UserSearch {
 *   }
 *
 * After:
 *   use Drupal\search_user\Plugin\Search\SearchUser;
 *   
 *   class MyPlugin extends SearchUser {
 *   }
 *
 * Caveats:
 *   Uses require the search_user submodule to be enabled alongside
 *   search; RenameClassRector only rewrites the PHP-level reference, so
 *   module dependency declarations (info.yml) must still be updated
 *   manually if a contrib module extends this class.
 *
 * @see https://www.drupal.org/node/3588379
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\user\\Plugin\\Search\\UserSearch' => 'Drupal\\search_user\\Plugin\\Search\\SearchUser',
    ]);
