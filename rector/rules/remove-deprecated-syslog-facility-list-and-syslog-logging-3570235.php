<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3570235
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes standalone calls to syslog_facility_list() and
// syslog_logging_settings_submit(), deprecated in drupal:11.4.0 and
// removed in drupal:13.0.0 / drupal:12.0.0 respectively with no
// replacement (issue #3570235). Both functions were moved into protected
// methods of internal classes and are no longer part of the public API.
// External callers should simply drop these calls.
//
// Before:
//   function my_submit($form, FormStateInterface $form_state): void {
//     syslog_logging_settings_submit($form, $form_state);
//     syslog_facility_list();
//   }
//
// After:
//   function my_submit($form, FormStateInterface $form_state): void
//   {
//   }


use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Rector\Config\RectorConfig;

// Removes calls to syslog_facility_list() and syslog_logging_settings_submit(),
// deprecated in drupal:11.4.0 with no replacement.
//
// syslog_logging_settings_submit() is removed in drupal:12.0.0.
// syslog_facility_list() is removed in drupal:13.0.0.
//
// Both functions were moved as protected methods into internal classes and are
// no longer part of the public API. External callers should drop these calls.
//
// @see https://www.drupal.org/node/3566774
// @see https://www.drupal.org/project/drupal/issues/3570235
