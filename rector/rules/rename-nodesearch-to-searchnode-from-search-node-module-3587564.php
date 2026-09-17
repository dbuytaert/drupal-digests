<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces Drupal\node\Plugin\Search\NodeSearch with
 * Drupal\search_node\Plugin\Search\SearchNode following the move of node
 * search functionality into the dedicated search_node sub-module. The
 * old class is deprecated in Drupal 11.4.0 and will be removed in
 * 12.0.0. All references — extends, type hints, new, and instanceof —
 * are updated automatically.
 *
 * Before:
 *   use Drupal\node\Plugin\Search\NodeSearch;
 *   
 *   class MyPlugin extends NodeSearch {
 *   }
 *
 * After:
 *   use Drupal\search_node\Plugin\Search\SearchNode;
 *   
 *   class MyPlugin extends SearchNode {
 *   }
 *
 * @see https://www.drupal.org/node/3587564
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\node\\Plugin\\Search\\NodeSearch' => 'Drupal\\search_node\\Plugin\\Search\\SearchNode',
    ]);
