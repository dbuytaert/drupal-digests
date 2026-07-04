<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames locale_config_batch_refresh_name() to
 * locale_config_batch_update_config_translations() and
 * locale_config_batch_set_config_langcodes() to
 * locale_config_batch_update_default_config_langcodes(). Both procedural
 * functions were deprecated in drupal:11.1.0 and removed in
 * drupal:12.0.0 in favour of identically-behaving replacements with
 * clearer names.
 *
 * Before:
 *   locale_config_batch_refresh_name($names, $langcodes, $context);
 *   locale_config_batch_set_config_langcodes($context);
 *
 * After:
 *   locale_config_batch_update_config_translations($names, $langcodes, $context);
 *   locale_config_batch_update_default_config_langcodes($context);
 *
 * @see https://www.drupal.org/node/3575254
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameFunctionRector::class, [
        'locale_config_batch_refresh_name' => 'locale_config_batch_update_config_translations',
        'locale_config_batch_set_config_langcodes' => 'locale_config_batch_update_default_config_langcodes',
    ]);
