<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In Drupal 11.2, StatementPrefetchIterator and StatementWrapperIterator
 * became subclasses of the new StatementBase abstract class. The
 * $defaultFetchMode property on both classes is deprecated (removed in
 * drupal:12.0.0) and replaced by $fetchMode inherited from
 * StatementBase. This rule renames all $defaultFetchMode accesses and
 * declarations in code that extends either class.
 *
 * Before:
 *   class MyStatement extends StatementPrefetchIterator {
 *     public function getMode(): FetchAs {
 *       return $this->defaultFetchMode;
 *     }
 *   }
 *
 * After:
 *   class MyStatement extends StatementPrefetchIterator {
 *     public function getMode(): FetchAs {
 *       return $this->fetchMode;
 *     }
 *   }
 *
 * @see https://www.drupal.org/node/3488467
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\PropertyFetch\RenamePropertyRector;
use Rector\Renaming\ValueObject\RenameProperty;
