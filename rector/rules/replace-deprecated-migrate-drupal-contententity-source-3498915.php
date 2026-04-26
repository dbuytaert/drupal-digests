<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3498915
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces references to the deprecated
// Drupal\migrate_drupal\Plugin\migrate\source\ContentEntity and
// ContentEntityDeriver classes with their replacements in the
// Drupal\migrate namespace. Both classes were moved from migrate_drupal
// to migrate in Drupal 11.2.0 and will be removed in Drupal 12.0.0 as
// part of the planned removal of the migrate_drupal module.
//
// Before:
//   use Drupal\migrate_drupal\Plugin\migrate\source\ContentEntity;
//   
//   class MySource extends ContentEntity {}
//
// After:
//   use Drupal\migrate_drupal\Plugin\migrate\source\ContentEntity;
//   
//   class MySource extends \Drupal\migrate\Plugin\migrate\source\ContentEntity {}


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
