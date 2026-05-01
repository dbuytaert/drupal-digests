<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the four deprecated global PHP constants
 * JSONAPI_FILTER_AMONG_ALL, JSONAPI_FILTER_AMONG_PUBLISHED,
 * JSONAPI_FILTER_AMONG_ENABLED, and JSONAPI_FILTER_AMONG_OWN with their
 * equivalents on Drupal\jsonapi\JsonApiFilter. The global constants were
 * deprecated in Drupal 11.3 and will be removed in 13.0 (issue
 * #3495600).
 *
 * Before:
 *   return [
 *       JSONAPI_FILTER_AMONG_ALL => AccessResult::allowed(),
 *       JSONAPI_FILTER_AMONG_PUBLISHED => AccessResult::neutral(),
 *       JSONAPI_FILTER_AMONG_ENABLED => AccessResult::forbidden(),
 *       JSONAPI_FILTER_AMONG_OWN => AccessResult::allowed(),
 *   ];
 *
 * After:
 *   return [
 *       \Drupal\jsonapi\JsonApiFilter::AMONG_ALL => AccessResult::allowed(),
 *       \Drupal\jsonapi\JsonApiFilter::AMONG_PUBLISHED => AccessResult::neutral(),
 *       \Drupal\jsonapi\JsonApiFilter::AMONG_ENABLED => AccessResult::forbidden(),
 *       \Drupal\jsonapi\JsonApiFilter::AMONG_OWN => AccessResult::allowed(),
 *   ];
 *
 * @see https://www.drupal.org/node/3495600
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ConstFetch\ConstFetchToClassConstFetchRector;
use Rector\Transform\ValueObject\ConstFetchToClassConstFetch;
