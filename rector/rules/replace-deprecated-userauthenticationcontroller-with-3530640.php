<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces Drupal\user\Controller\UserAuthenticationController with
 * Drupal\rest\Controller\RestAuthenticationController, which was
 * introduced in Drupal 11.4.0 as part of moving the REST
 * login/logout/password-reset routes from the user module to the rest
 * module. The old class is deprecated in drupal:11.4.0 and removed in
 * drupal:12.0.0. Enabling the rest module is required at runtime.
 *
 * Before:
 *   use Drupal\user\Controller\UserAuthenticationController;
 *   
 *   class MyController extends UserAuthenticationController {}
 *
 * After:
 *   use Drupal\rest\Controller\RestAuthenticationController;
 *   
 *   class MyController extends RestAuthenticationController {}
 *
 * Caveats:
 *   Only renames PHP references to the class. Route name strings like
 *   user.login.http used in Url::fromRoute() calls must be updated
 *   manually to rest.login (and similarly for user.logout.http,
 *   user.login_status.http, user.pass.http). The rest module must be
 *   enabled for the new routes to exist.
 *
 * @see https://www.drupal.org/node/3530640
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
