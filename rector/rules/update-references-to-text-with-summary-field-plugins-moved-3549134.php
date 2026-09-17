<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal core moved the text_with_summary field type, its
 * text_textarea_with_summary widget, and its text_summary_or_trimmed
 * formatter out of the text module into a new dedicated
 * text_with_summary module. The old classes in
 * Drupal\text\Plugin\Field\... are deprecated in drupal:11.5.0 and
 * removed in drupal:12.0.0. This rule rewrites use, extends, instanceof,
 * new, and type-hint references from the old class locations to their
 * new Drupal\text_with_summary\Plugin\Field\... equivalents so custom
 * subclasses and type checks keep working after the module split.
 *
 * Before:
 *   use Drupal\text\Plugin\Field\FieldType\TextWithSummaryItem;
 *   
 *   class MyItem extends TextWithSummaryItem {
 *   }
 *
 * After:
 *   class MyItem extends \Drupal\text_with_summary\Plugin\Field\FieldType\TextWithSummaryItem {
 *   }
 *
 * Caveats:
 *   The rule rewrites the code reference itself but does not remove the
 *   now-unused use statement for the old class, and it does not add
 *   text_with_summary as a module dependency in the consuming module's
 *   .info.yml; both are cosmetic/config follow-ups a developer should
 *   make by hand.
 *
 * @see https://www.drupal.org/node/3549134
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\text\Plugin\Field\FieldType\TextWithSummaryItem' => 'Drupal\text_with_summary\Plugin\Field\FieldType\TextWithSummaryItem',
        'Drupal\text\Plugin\Field\FieldWidget\TextareaWithSummaryWidget' => 'Drupal\text_with_summary\Plugin\Field\FieldWidget\TextareaWithSummaryWidget',
        'Drupal\text\Plugin\Field\FieldFormatter\TextSummaryOrTrimmedFormatter' => 'Drupal\text_with_summary\Plugin\Field\FieldFormatter\TextSummaryOrTrimmedFormatter',
    ]);
