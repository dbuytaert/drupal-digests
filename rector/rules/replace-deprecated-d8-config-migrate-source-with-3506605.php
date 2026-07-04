<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces references to the deprecated
 * Drupal\migrate_drupal\Plugin\migrate\source\d8\Config class with its
 * successor Drupal\migrate\Plugin\migrate\source\ConfigEntity. The old
 * class was deprecated in Drupal 11.2.0 and will be removed in 12.0.0 as
 * part of migrating the d8_config source plugin from migrate_drupal into
 * the core migrate module.
 *
 * Before:
 *   use Drupal\migrate_drupal\Plugin\migrate\source\d8\Config;
 *   
 *   class MyMigrationSource extends Config {}
 *
 * After:
 *   class MyMigrationSource extends \Drupal\migrate\Plugin\migrate\source\ConfigEntity {}
 *
 * @see https://www.drupal.org/node/3506605
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()->withRules([RenameClassRector::class])->withConfiguredRule(RenameClassRector::class, [
    'Drupal\migrate_drupal\Plugin\migrate\source\d8\Config' => 'Drupal\migrate\Plugin\migrate\source\ConfigEntity',
]);
