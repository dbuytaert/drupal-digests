<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes the deprecated first argument from calls to
 * Drupal\Core\Entity\Query\Sql\Query::getTables(). Passing a
 * SelectInterface argument was deprecated in Drupal 11.4.0 as part of
 * the SQL entity query join optimisation that shares a single Tables
 * instance per query. Contrib modules that subclass Query and call
 * $this->getTables($sql_query) must drop the argument.
 *
 * Before:
 *   $tables = $this->getTables($sql_query);
 *
 * After:
 *   $tables = $this->getTables();
 *
 * Caveats:
 *   Does not cover calls to the @internal doGetTables() method
 *   introduced in the same change, as that method is not intended for
 *   contrib use. Does not handle the second deprecation from this issue
 *   (passing a Condition from a different query into condition()),
 *   which is a runtime-only check and cannot be detected statically.
 *
 * @see https://www.drupal.org/node/2875033
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use Rector\Arguments\Rector\MethodCall\RemoveMethodCallParamRector;
use Rector\Arguments\ValueObject\RemoveMethodCallParam;
use Rector\Config\RectorConfig;
