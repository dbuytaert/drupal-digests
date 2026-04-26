<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3562361
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Applies Rector's typeDeclarations set to PHPUnit test code, inferring
// and adding void return types (and other inferrable types) to test
// methods such as setUp, tearDown, and test* methods. Property-typing
// rules are intentionally skipped because they carry more risk and are
// better handled separately. This matches the pattern Drupal core used
// in issue #3562361.
//
// Before:
//   use PHPUnit\Framework\TestCase;
//   
//   class SomeTest extends TestCase
//   {
//       public function setUp()
//       {
//           parent::setUp();
//       }
//   
//       public function tearDown()
//       {
//           parent::tearDown();
//       }
//   
//       public function testSomething()
//       {
//           $this->assertTrue(true);
//       }
//   }
//
// After:
//   use PHPUnit\Framework\TestCase;
//   
//   class SomeTest extends TestCase
//   {
//       public function setUp(): void
//       {
//           parent::setUp();
//       }
//   
//       public function tearDown(): void
//       {
//           parent::tearDown();
//       }
//   
//       public function testSomething(): void
//       {
//           $this->assertTrue(true);
//       }
//   }


// Adds void (and other inferrable) return type hints to PHPUnit test methods.
// Mirrors the approach used in Drupal core issue #3562361.
// Property-typing rules are excluded; handle those in a separate pass.
//
// Point Rector at your module's test directory, e.g.:
//   ->withPaths([__DIR__ . '/tests'])
//
// Then run: ./vendor/bin/rector process

use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictSetUpRector;
use Rector\Config\RectorConfig;
