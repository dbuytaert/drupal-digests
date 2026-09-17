<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated
 * Drupal\menu_ui\Plugin\Menu\LocalAction\MenuLinkAdd class with the new
 * Drupal\Core\Menu\LocalActionWithDestination class introduced in Drupal
 * 11.2. The old class was a menu_ui-specific helper; the replacement
 * lives in Drupal core and is available to all modules. This rule
 * updates class extensions, type hints, instanceof checks, and new
 * expressions.
 *
 * Before:
 *   use Drupal\menu_ui\Plugin\Menu\LocalAction\MenuLinkAdd;
 *   
 *   class MyLocalAction extends MenuLinkAdd {}
 *
 * After:
 *   use Drupal\Core\Menu\LocalActionWithDestination;
 *   
 *   class MyLocalAction extends LocalActionWithDestination {}
 *
 * @see https://www.drupal.org/node/2762131
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return RectorConfig::configure()
    ->withConfiguredRule(RenameClassRector::class, [
        'Drupal\\menu_ui\\Plugin\\Menu\\LocalAction\\MenuLinkAdd' => 'Drupal\\Core\\Menu\\LocalActionWithDestination',
    ]);
