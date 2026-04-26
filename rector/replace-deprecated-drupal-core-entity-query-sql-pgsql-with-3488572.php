<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-drupal-core-entity-query-sql-pgsql-with-3488572.php';

return RectorConfig::configure()
    ->withRules([RenamePgsqlEntityQueryClassesRector::class]);
