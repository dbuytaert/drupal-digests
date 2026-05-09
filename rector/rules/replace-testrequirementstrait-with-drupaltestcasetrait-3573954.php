<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces use TestRequirementsTrait with use DrupalTestCaseTrait in
 * test classes. \Drupal\Tests\TestRequirementsTrait is deprecated in
 * drupal:12.0.0 and removed in drupal:13.0.0;
 * \Drupal\Tests\DrupalTestCaseTrait is its replacement, providing the
 * $root property via a hooked getter and consolidating shared test
 * infrastructure methods.
 *
 * Before:
 *   use Drupal\Tests\TestRequirementsTrait;
 *   
 *   class MyTest extends KernelTestBase {
 *     use TestRequirementsTrait;
 *   }
 *
 * After:
 *   use Drupal\Tests\DrupalTestCaseTrait;
 *   
 *   class MyTest extends KernelTestBase {
 *     use DrupalTestCaseTrait;
 *   }
 *
 * Caveats:
 *   The old use Drupal\Tests\TestRequirementsTrait; import statement is
 *   left as a dead unused import after the rename; pair with a dead-
 *   import removal pass (e.g. RemoveUnusedImportsRector) to clean it
 *   up. Calls to the now-deprecated getDrupalRoot() or setUpAppRoot()
 *   are not rewritten by this rule.
 *
 * @see https://www.drupal.org/node/3573954
 * @deprecated drupal:12.0.0
 * @removed drupal:13.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
