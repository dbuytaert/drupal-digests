<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3560075
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated
// Drupal\menu_link_content\Plugin\migrate\process\LinkOptions and
// LinkUri classes with their new equivalents in the
// Drupal\migrate\Plugin\migrate\process namespace. Both old classes were
// deprecated in Drupal 11.4.0 and will be removed in 13.0.0. Custom code
// that extends or instantiates them must be updated to avoid the
// deprecation warnings.
//
// Before:
//   use Drupal\menu_link_content\Plugin\migrate\process\LinkOptions;
//   use Drupal\menu_link_content\Plugin\migrate\process\LinkUri;
//   
//   class MyCustomProcess extends LinkOptions {}
//   class MyCustomLinkUri extends LinkUri {}
//
// After:
//   use Drupal\migrate\Plugin\migrate\process\LinkOptions;
//   use Drupal\migrate\Plugin\migrate\process\LinkUri;
//   
//   class MyCustomProcess extends LinkOptions {}
//   class MyCustomLinkUri extends LinkUri {}


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

// Replaces deprecated migrate process plugin classes from menu_link_content
// module with their replacements in the migrate module, as introduced in
// Drupal 11.4.0. See https://www.drupal.org/node/3572239
