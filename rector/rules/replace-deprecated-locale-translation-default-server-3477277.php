<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated global constant
 * LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN with the class constant
 * \Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN. The constant was moved to
 * the Drupal class in drupal:11.2.0 and the old global constant is
 * removed in drupal:12.0.0. Using the class constant avoids a dependency
 * on the locale module being available.
 *
 * Before:
 *   $pattern = LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN;
 *
 * After:
 *   $pattern = \Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN;
 *
 * @see https://www.drupal.org/node/3477277
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ConstFetch\ConstFetchToClassConstFetchRector;
use Rector\Transform\ValueObject\ConstFetchToClassConstFetch;

return RectorConfig::configure()
    ->withConfiguredRule(ConstFetchToClassConstFetchRector::class, [
        new ConstFetchToClassConstFetch(
            'LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN',
            'Drupal',
            'TRANSLATION_DEFAULT_SERVER_PATTERN'
        ),
    ]);
