<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to CacheBackendInterface::invalidateAll(), deprecated
 * in Drupal 11.2.0 and removed in 12.0.0, with deleteAll(). The
 * invalidateAll() method was unnecessary because deleting is the correct
 * action in all practical cases, and its existence created
 * implementation overhead in backends like Redis. The rule targets any
 * variable or expression typed as CacheBackendInterface.
 *
 * Before:
 *   $cache->invalidateAll();
 *
 * After:
 *   $cache->deleteAll();
 *
 * Caveats:
 *   When the original intent was to keep invalidated items accessible
 *   via get(..., TRUE) rather than to fully purge them, deleteAll()
 *   changes semantics. In those rare cases manual review is needed. The
 *   rule cannot detect that nuance automatically.
 *
 * @see https://www.drupal.org/node/3498947
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
