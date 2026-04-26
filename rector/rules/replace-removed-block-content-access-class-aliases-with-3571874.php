<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571874
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the four Drupal\block_content\Access class aliases
// (AccessGroupAnd, DependentAccessInterface,
// RefinableDependentAccessInterface, RefinableDependentAccessTrait) to
// their canonical Drupal\Core\Access homes. The aliases were introduced
// in drupal:11.2.0 (change record #3527501) and removed in drupal:12.0.0
// (issue #3571874), causing a fatal class-not-found error for any module
// still referencing the old namespace.
//
// Before:
//   use Drupal\block_content\Access\RefinableDependentAccessInterface;
//   use Drupal\block_content\Access\RefinableDependentAccessTrait;
//   
//   class MyBlock implements RefinableDependentAccessInterface {
//     use RefinableDependentAccessTrait;
//   }
//
// After:
//   use Drupal\Core\Access\RefinableDependentAccessInterface;
//   use Drupal\Core\Access\RefinableDependentAccessTrait;
//   
//   class MyBlock implements RefinableDependentAccessInterface {
//     use RefinableDependentAccessTrait;
//   }


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

/**
 * Replaces the four block_content\Access class aliases removed in drupal:12.0.0.
 *
 * AccessGroupAnd, DependentAccessInterface, RefinableDependentAccessInterface,
 * and RefinableDependentAccessTrait were moved from Drupal\block_content\Access
 * to Drupal\Core\Access in drupal:11.2.0 (change record #3527501). The old
 * names were kept as backward-compatibility class aliases and deprecated; those
 * aliases were removed in drupal:12.0.0 by issue #3571874. Any contrib or
 * custom module still using the old namespace will get a fatal "class not
 * found" error on drupal:12.
 *
 * @see https://www.drupal.org/node/3527501
 * @see https://www.drupal.org/project/drupal/issues/3571874
 */
