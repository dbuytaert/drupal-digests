<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-system-region-list-and-system-default-region-with-3015812.php';

return RectorConfig::configure()
    ->withRules([SystemRegionFunctionsRector::class]);
