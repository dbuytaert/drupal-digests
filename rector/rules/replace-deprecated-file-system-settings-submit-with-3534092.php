<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated procedural function
 * file_system_settings_submit() with the equivalent static method
 * \Drupal\file\Hook\FileHooks::settingsSubmit(). The function was
 * deprecated in Drupal 11.3.0 and will be removed in 12.0.0 as part of
 * moving form submit handlers into hook classes.
 *
 * Before:
 *   file_system_settings_submit($form, $form_state);
 *
 * After:
 *   \Drupal\file\Hook\FileHooks::settingsSubmit($form, $form_state);
 *
 * @see https://www.drupal.org/node/3534092
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
