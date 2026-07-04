<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames AliasWhitelist to AliasPrefixList and AliasWhitelistInterface
 * to AliasPrefixListInterface throughout contrib code, and renames the
 * protected method pathAliasWhitelistRebuild to
 * pathAliasPrefixListRebuild on AliasManager subclasses. These symbols
 * were deprecated in Drupal 11.1 as part of removing whitelist/blacklist
 * terminology from the path_alias module.
 *
 * Before:
 *   use Drupal\path_alias\AliasWhitelist;
 *   use Drupal\path_alias\AliasWhitelistInterface;
 *   
 *   $list = new AliasWhitelist(...);
 *   function build(AliasWhitelistInterface $w): AliasWhitelistInterface {}
 *
 * After:
 *   use Drupal\path_alias\AliasPrefixList;
 *   use Drupal\path_alias\AliasPrefixListInterface;
 *   
 *   $list = new AliasPrefixList(...);
 *   function build(AliasPrefixListInterface $w): AliasPrefixListInterface {}
 *
 * @see https://www.drupal.org/node/3151086
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\path_alias\\AliasWhitelist' => 'Drupal\\path_alias\\AliasPrefixList',
        'Drupal\\path_alias\\AliasWhitelistInterface' => 'Drupal\\path_alias\\AliasPrefixListInterface',
    ])
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename('Drupal\\path_alias\\AliasManager', 'pathAliasWhitelistRebuild', 'pathAliasPrefixListRebuild'),
    ]);
