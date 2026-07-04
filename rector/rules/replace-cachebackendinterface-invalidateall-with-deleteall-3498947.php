<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to CacheBackendInterface::invalidateAll() with
 * CacheBackendInterface::deleteAll(). The invalidateAll() method is
 * deprecated in Drupal 11.2.0 and removed in 12.0.0 because it was an
 * unnecessary and expensive alternative to deleteAll() with no valid use
 * case for most callers. Contrib modules calling invalidateAll() on
 * typed CacheBackendInterface receivers are updated automatically.
 *
 * Before:
 *   $cache->invalidateAll();
 *
 * After:
 *   $cache->deleteAll();
 *
 * Caveats:
 *   Dynamic calls such as \Drupal::cache('menu')->invalidateAll() are
 *   not rewritten because PHPStan cannot infer the return type of
 *   \Drupal::cache() without the full Drupal autoload path. Those call
 *   sites must be updated manually.
 *
 * @see https://www.drupal.org/node/3498947
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return RectorConfig::configure()
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename('Drupal\Core\Cache\CacheBackendInterface', 'invalidateAll', 'deleteAll'),
    ]);
