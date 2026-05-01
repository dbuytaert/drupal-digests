<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-system-sort-themes-callback-with-inline-3571172.php';

return RectorConfig::configure()
    ->withRules([SystemSortThemesRector::class]);
