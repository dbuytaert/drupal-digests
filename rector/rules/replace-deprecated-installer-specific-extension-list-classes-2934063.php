<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal\Core\Installer\InstallerModuleExtensionList and
 * InstallerThemeExtensionList are now empty backward-compatibility shims
 * around Drupal\Core\Extension\ModuleExtensionList and
 * ThemeExtensionList; the installer no longer swaps them into the
 * container. This rule rewrites type hints, property types, new calls,
 * instanceof checks, and use imports from the deprecated installer
 * classes to their base-class equivalents, which provide identical
 * behavior.
 *
 * Before:
 *   use Drupal\Core\Installer\InstallerModuleExtensionList;
 *   
 *   class MyService {
 *     public function __construct(InstallerModuleExtensionList $module_list) {}
 *   }
 *
 * After:
 *   use Drupal\Core\Extension\ModuleExtensionList;
 *   
 *   class MyService {
 *     public function __construct(ModuleExtensionList $module_list) {}
 *   }
 *
 * Caveats:
 *   Only rewrites the class references. It does not touch calls to the
 *   now-deprecated ExtensionList::setPathname() method that some
 *   contrib code may still call on the resulting object; that method
 *   has no direct replacement and is out of scope for this rule.
 *
 * @see https://www.drupal.org/node/2934063
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\Core\\Installer\\InstallerModuleExtensionList' => 'Drupal\\Core\\Extension\\ModuleExtensionList',
        'Drupal\\Core\\Installer\\InstallerThemeExtensionList' => 'Drupal\\Core\\Extension\\ThemeExtensionList',
    ]);
