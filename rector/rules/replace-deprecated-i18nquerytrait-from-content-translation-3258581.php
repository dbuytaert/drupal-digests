<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces use
 * Drupal\content_translation\Plugin\migrate\source\I18nQueryTrait with
 * use Drupal\migrate_drupal\Plugin\migrate\source\I18nQueryTrait. The
 * trait was moved because it is only required for migrate_drupal source
 * plugins, not for content_translation itself. Using the old location
 * triggers a deprecation notice and will be removed in Drupal 12.
 *
 * Before:
 *   use Drupal\content_translation\Plugin\migrate\source\I18nQueryTrait;
 *   
 *   class BlockCustomTranslation extends DrupalSqlBase {
 *     use I18nQueryTrait;
 *   }
 *
 * After:
 *   use Drupal\migrate_drupal\Plugin\migrate\source\I18nQueryTrait;
 *   
 *   class BlockCustomTranslation extends DrupalSqlBase {
 *     use I18nQueryTrait;
 *   }
 *
 * Caveats:
 *   When the trait is used via a short-name import (use
 *   I18nQueryTrait;), RenameClassRector rewrites the class-body trait
 *   use to the fully qualified new name but leaves the old use import
 *   statement as dead code. The code runs correctly; a separate dead-
 *   import removal pass (e.g. PHPStan or PHP-CS-Fixer) can clean it up.
 *
 * @see https://www.drupal.org/node/3258581
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\content_translation\\Plugin\\migrate\\source\\I18nQueryTrait' => 'Drupal\\migrate_drupal\\Plugin\\migrate\\source\\I18nQueryTrait',
    ]);
