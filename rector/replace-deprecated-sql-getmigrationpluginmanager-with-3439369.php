<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-sql-getmigrationpluginmanager-with-3439369.php';

return RectorConfig::configure()
    ->withRules([MigrateSqlGetMigrationPluginManagerRector::class]);
