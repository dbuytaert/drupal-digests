<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces all references to the deprecated
 * \Drupal\menu_ui\Plugin\Menu\LocalAction\MenuLinkAdd with the new core
 * class \Drupal\Core\Menu\LocalActionWithDestination. MenuLinkAdd was
 * deprecated in drupal:11.2.0 and will be removed in drupal:12.0.0. This
 * affects extends, use imports, type hints, instanceof checks, and other
 * class references in contrib and custom modules.
 *
 * Before:
 *   use Drupal\menu_ui\Plugin\Menu\LocalAction\MenuLinkAdd;
 *   
 *   class MyLocalAction extends MenuLinkAdd {}
 *
 * After:
 *   class MyLocalAction extends \Drupal\Core\Menu\LocalActionWithDestination {}
 *
 * @see https://www.drupal.org/node/2762131
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
