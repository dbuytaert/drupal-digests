<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes use PhpUnitCompatibilityTrait; from test classes and its
 * corresponding use Drupal\Tests\PhpUnitCompatibilityTrait; import. The
 * trait was a forward-compatibility shim for PHPUnit API differences
 * across versions; it became a no-op from PHPUnit 11 onward and was
 * deleted from Drupal core in issue #3582118. Any contrib or custom
 * module test class still referencing it will cause a fatal error.
 *
 * Before:
 *   use Drupal\Tests\PhpUnitCompatibilityTrait;
 *   use PHPUnit\Framework\TestCase;
 *   
 *   class ExampleTest extends TestCase {
 *     use PhpUnitCompatibilityTrait;
 *   }
 *
 * After:
 *   use PHPUnit\Framework\TestCase;
 *   
 *   class ExampleTest extends TestCase {
 *   }
 *
 * @see https://www.drupal.org/node/3582118
 */


use Rector\Config\RectorConfig;
use Rector\Removing\Rector\Class_\RemoveTraitUseRector;
