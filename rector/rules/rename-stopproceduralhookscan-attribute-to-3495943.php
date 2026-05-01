<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames the StopProceduralHookScan PHP attribute class to
 * ProceduralHookScanStop as part of Drupal 11.2's OOP preprocess hooks
 * refactor. The old class was removed without a deprecation shim. Any
 * file that uses #[StopProceduralHookScan] to signal the end of
 * procedural hook scanning must adopt the new name to remain compatible
 * with Drupal 11.2+.
 *
 * Before:
 *   use Drupal\Core\Hook\Attribute\StopProceduralHookScan;
 *   
 *   #[StopProceduralHookScan]
 *   function my_module_last_procedural_hook(): void {}
 *
 * After:
 *   use Drupal\Core\Hook\Attribute\ProceduralHookScanStop;
 *   
 *   #[ProceduralHookScanStop]
 *   function my_module_last_procedural_hook(): void {}
 *
 * Caveats:
 *   After the rename the now-unused use
 *   Drupal\Core\Hook\Attribute\StopProceduralHookScan; import may be
 *   left as a dead statement; a separate RemoveUnusedImportRector pass
 *   can clean it up. The hooks_converted → skip_procedural_hook_scan
 *   parameter rename in .services.yml files is not covered, as Rector
 *   operates only on PHP.
 *
 * @see https://www.drupal.org/node/3495943
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
