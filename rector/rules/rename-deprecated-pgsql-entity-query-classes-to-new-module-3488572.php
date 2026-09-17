<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces references to the deprecated
 * Drupal\Core\Entity\Query\Sql\pgsql\Condition and
 * Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory classes with their new
 * counterparts in the Drupal\pgsql\EntityQuery namespace. Both classes
 * were moved to the pgsql module in Drupal 11.2.0. Contrib modules
 * extending or instantiating these classes need this update to avoid
 * deprecation warnings and prepare for Drupal 12.
 *
 * Before:
 *   use Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory;
 *   
 *   class MyQueryFactory extends QueryFactory {}
 *
 * After:
 *   use Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory;
 *   
 *   class MyQueryFactory extends \Drupal\pgsql\EntityQuery\QueryFactory {}
 *
 * Caveats:
 *   The old use import statement is not removed automatically; it
 *   becomes a dead import. Run RemoveUnusedImportsRector afterward to
 *   clean it up.
 *
 * @see https://www.drupal.org/node/3488572
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\Core\Entity\Query\Sql\pgsql\Condition' => 'Drupal\pgsql\EntityQuery\Condition',
        'Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory' => 'Drupal\pgsql\EntityQuery\QueryFactory',
    ]);
