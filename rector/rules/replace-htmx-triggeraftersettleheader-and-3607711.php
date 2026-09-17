<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames the deprecated triggerAfterSettleHeader() and
 * triggerAfterSwapHeader() methods on Drupal\Core\Htmx\Htmx to
 * triggerHeader(). Both methods were deprecated in Drupal 11.5.0 in
 * preparation for the htmx v4 upgrade and will be removed in 12.0.0.
 * Contrib modules using the Htmx builder API can be upgraded
 * automatically.
 *
 * Before:
 *   $htmx->triggerAfterSettleHeader('myEvent');
 *   $htmx->triggerAfterSwapHeader(['key' => 'value']);
 *
 * After:
 *   $htmx->triggerHeader('myEvent');
 *   $htmx->triggerHeader(['key' => 'value']);
 *
 * Caveats:
 *   Other deprecated Htmx methods (disabledElt, disinherit, ext,
 *   history, inherit, params, prompt, request) and HtmxRequestInfoTrait
 *   methods (getHtmxPrompt, getHtmxTrigger) have no direct programmatic
 *   replacement and cannot be automatically rewritten; those require
 *   manual review of the change record at
 *   https://www.drupal.org/node/3583674.
 *
 * @see https://www.drupal.org/node/3607711
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;

return RectorConfig::configure()
    ->withConfiguredRule(RenameMethodRector::class, [
        new MethodCallRename('Drupal\Core\Htmx\Htmx', 'triggerAfterSettleHeader', 'triggerHeader'),
        new MethodCallRename('Drupal\Core\Htmx\Htmx', 'triggerAfterSwapHeader', 'triggerHeader'),
    ]);
