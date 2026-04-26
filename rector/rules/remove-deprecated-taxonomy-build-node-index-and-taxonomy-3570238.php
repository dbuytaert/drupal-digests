<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3570238
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes standalone calls to taxonomy_build_node_index() and
// taxonomy_delete_node_index(), deprecated in drupal:11.4.0 and removed
// in drupal:13.0.0 with no replacement (issue #3570238). The
// functionality was moved into protected methods of TaxonomyHooks and is
// now invoked exclusively through hooks. External callers should simply
// drop these calls.
//
// Before:
//   function my_module_node_insert($node): void {
//     taxonomy_build_node_index($node);
//   }
//   
//   function my_module_node_delete($node): void {
//     taxonomy_delete_node_index($node);
//   }
//
// After:
//   function my_module_node_insert($node): void {
//   }
//   
//   function my_module_node_delete($node): void {
//   }


use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Config\RectorConfig;

// Removes calls to taxonomy_build_node_index() and taxonomy_delete_node_index(),
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0 with no replacement.
// Both functions were moved as protected methods into TaxonomyHooks and are
// invoked automatically through hooks; external callers should simply remove
// any direct calls to these functions.
//
// @see https://www.drupal.org/node/3566774
// @see https://www.drupal.org/project/drupal/issues/3570238
