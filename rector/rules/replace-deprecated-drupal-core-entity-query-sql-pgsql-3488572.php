<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory and
 * Drupal\Core\Entity\Query\Sql\pgsql\Condition to their new homes in
 * Drupal\pgsql\EntityQuery\. Both classes were moved to the dedicated
 * pgsql module in Drupal 11.2.0 and the old locations are removed in
 * 12.0.0.
 *
 * Before:
 *   use Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory;
 *   use Drupal\Core\Entity\Query\Sql\pgsql\Condition;
 *
 * After:
 *   use Drupal\pgsql\EntityQuery\QueryFactory;
 *   use Drupal\pgsql\EntityQuery\Condition;
 *
 * @see https://www.drupal.org/node/3488572
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
