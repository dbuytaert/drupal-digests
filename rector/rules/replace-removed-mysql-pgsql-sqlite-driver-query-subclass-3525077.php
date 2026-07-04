<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes references to the nine empty driver-specific query subclasses
 * (mysql\Delete, mysql\Select, mysql\Merge, mysql\Truncate,
 * mysql\Update, pgsql\Merge, sqlite\Delete, sqlite\Merge, sqlite\Update)
 * that were deprecated in drupal:11.0.0 and removed in drupal:12.0.0.
 * Any code extending or type-hinting these stubs must reference the
 * equivalent Drupal\Core\Database\Query\* base class directly.
 *
 * Before:
 *   use Drupal\mysql\Driver\Database\mysql\Delete;
 *   
 *   class MyDelete extends Delete {}
 *
 * After:
 *   class MyDelete extends \Drupal\Core\Database\Query\Delete {}
 *
 * Caveats:
 *   RenameClassRector rewrites class references in code bodies to FQCNs
 *   but does not remove the now-unused use statement for the old class.
 *   The stale import is dead code (PHP use is lazy and causes no
 *   runtime error when unused), but running RemoveUnusedImportsRector
 *   or an IDE "optimize imports" action after this rule will clean it
 *   up.
 *
 * @see https://www.drupal.org/node/3525077
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        // MySQL driver stubs removed in drupal:12.0.0 (deprecated since drupal:11.0.0).
        // See https://www.drupal.org/node/3256524
        'Drupal\\mysql\\Driver\\Database\\mysql\\Delete'   => 'Drupal\\Core\\Database\\Query\\Delete',
        'Drupal\\mysql\\Driver\\Database\\mysql\\Merge'    => 'Drupal\\Core\\Database\\Query\\Merge',
        'Drupal\\mysql\\Driver\\Database\\mysql\\Select'   => 'Drupal\\Core\\Database\\Query\\Select',
        'Drupal\\mysql\\Driver\\Database\\mysql\\Truncate' => 'Drupal\\Core\\Database\\Query\\Truncate',
        'Drupal\\mysql\\Driver\\Database\\mysql\\Update'   => 'Drupal\\Core\\Database\\Query\\Update',
        // PostgreSQL driver stub removed in drupal:12.0.0 (deprecated since drupal:11.0.0).
        'Drupal\\pgsql\\Driver\\Database\\pgsql\\Merge'    => 'Drupal\\Core\\Database\\Query\\Merge',
        // SQLite driver stubs removed in drupal:12.0.0 (deprecated since drupal:11.0.0).
        'Drupal\\sqlite\\Driver\\Database\\sqlite\\Delete' => 'Drupal\\Core\\Database\\Query\\Delete',
        'Drupal\\sqlite\\Driver\\Database\\sqlite\\Merge'  => 'Drupal\\Core\\Database\\Query\\Merge',
        'Drupal\\sqlite\\Driver\\Database\\sqlite\\Update' => 'Drupal\\Core\\Database\\Query\\Update',
    ]);
