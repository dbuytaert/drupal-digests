<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/strip-removed-expand-argument-from-getmigrationdependencies-3574717.php';

return RectorConfig::configure()
    ->withRules([RemoveMigrationDependenciesExpandArgRector::class]);
