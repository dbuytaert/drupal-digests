<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated \Drupal\Tests\TestRequirementsTrait with the
 * new \Drupal\Tests\DrupalTestCaseTrait in class body trait-use
 * statements. TestRequirementsTrait was deprecated in Drupal 12.0.0 when
 * its functionality was consolidated into DrupalTestCaseTrait. Contrib
 * and custom test classes that include the old trait directly need this
 * update to avoid deprecation warnings.
 *
 * Before:
 *   use Drupal\Tests\TestRequirementsTrait;
 *   
 *   class ExampleTest extends BrowserTestBase {
 *     use TestRequirementsTrait;
 *   }
 *
 * After:
 *   class ExampleTest extends BrowserTestBase {
 *     use \Drupal\Tests\DrupalTestCaseTrait;
 *   }
 *
 * Caveats:
 *   The old use Drupal\Tests\TestRequirementsTrait; namespace import is
 *   left in place as an unused import. Run RemoveUnusedImportsRector
 *   afterwards to clean it up. If the class already inherits
 *   DrupalTestCaseTrait from a base class (e.g., BrowserTestBase or
 *   KernelTestBase), the added trait use is redundant but harmless. The
 *   deprecated setUpAppRoot() calls and
 *   TestRequirementsTrait::getDrupalRoot() calls are not handled by
 *   this rule.
 *
 * @see https://www.drupal.org/node/3573954
 * @deprecated drupal:12.0.0
 * @removed drupal:13.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
