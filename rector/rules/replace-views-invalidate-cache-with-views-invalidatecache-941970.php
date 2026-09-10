<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * The global function views_invalidate_cache() is deprecated in favor of
 * \Drupal\views\Views::invalidateCache(). The new static method only
 * invalidates the views_data cache tag and invokes
 * hook_views_invalidate_cache(); it no longer forces a router rebuild,
 * since that is now handled per-display via the PostSaveViewInterface
 * machinery introduced in the same change. This rule rewrites simple
 * call sites so modules keep working after the function is removed.
 *
 * Before:
 *   views_invalidate_cache();
 *
 * After:
 *   \Drupal\views\Views::invalidateCache();
 *
 * Caveats:
 *   Only rewrites the cache-invalidation call itself. The old function
 *   also unconditionally called
 *   \Drupal::service('router.builder')->setRebuildNeeded(); callers
 *   that relied on views_invalidate_cache() to also trigger a router
 *   rebuild must add that call explicitly
 *   (\Drupal::service('router.builder')->setRebuildNeeded(); and, for
 *   Views' own route subscriber,
 *   \Drupal::service('views.route_subscriber')->reset();), since the
 *   replacement method no longer does this.
 *
 * @see https://www.drupal.org/node/941970
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameFunctionRector::class, [
        'views_invalidate_cache' => 'Drupal\\views\\Views::invalidateCache',
    ]);
