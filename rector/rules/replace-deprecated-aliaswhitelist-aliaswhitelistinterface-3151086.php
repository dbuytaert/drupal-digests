<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated AliasWhitelist class and
 * AliasWhitelistInterface interface (deprecated in Drupal 11.1.0,
 * removed in 12.0.0) with their replacements AliasPrefixList and
 * AliasPrefixListInterface in the path_alias module. Contrib and custom
 * modules that type-hint, implement, or instantiate the old whitelist
 * types must adopt the new prefix-list equivalents before upgrading to
 * Drupal 12.
 *
 * Before:
 *   class MyClass implements \Drupal\path_alias\AliasWhitelistInterface {
 *     public function factory(): \Drupal\path_alias\AliasWhitelist {
 *       return new \Drupal\path_alias\AliasWhitelist('cid', $cache, $lock, $state, $repo);
 *     }
 *   }
 *
 * After:
 *   class MyClass implements \Drupal\path_alias\AliasPrefixListInterface {
 *     public function factory(): \Drupal\path_alias\AliasPrefixList {
 *       return new \Drupal\path_alias\AliasPrefixList('cid', $cache, $lock, $state, $repo);
 *     }
 *   }
 *
 * Caveats:
 *   use import statements that reference the old class names (e.g. use
 *   Drupal\path_alias\AliasWhitelistInterface;) are not updated by this
 *   rule and will become unused imports; a separate unused-import
 *   removal tool is needed to clean them up. The protected method
 *   pathAliasWhitelistRebuild on AliasManager is also deprecated but
 *   not covered here, as only subclasses of AliasManager that override
 *   it are affected.
 *
 * @see https://www.drupal.org/node/3151086
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
