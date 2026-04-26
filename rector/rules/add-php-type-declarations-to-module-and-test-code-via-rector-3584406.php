<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3584406
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Applies Rector's built-in typeDeclarations prepared set to
// automatically infer and add PHP return types, parameter types, and
// property types to existing code. Running this periodically on module
// and test directories reduces PHPStan noise and modernises the codebase
// toward strict PHP typing without manual effort.
//
// Before:
//   <?php
//   class MyController {
//     public function build() {
//       return ['#markup' => 'Hello'];
//     }
//   
//     public function doSomething() {
//       \Drupal::state()->set('key', TRUE);
//     }
//   }
//
// After:
//   <?php
//   class MyController {
//     public function build(): array {
//       return ['#markup' => 'Hello'];
//     }
//   
//     public function doSomething(): void {
//       \Drupal::state()->set('key', TRUE);
//     }
//   }


use Rector\Config\RectorConfig;

// Run from your Drupal project root:
//   php vendor/bin/rector process web/modules/custom --config rector-type-declarations.php
// Or target your module's tests directory:
//   php vendor/bin/rector process web/modules/custom/mymodule/tests --config rector-type-declarations.php
