<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-pdo-fetch-constants-with-fetchas-enum-cases-in-3525077.php';

return RectorConfig::configure()
    ->withRules([PdoFetchConstToFetchAsRector::class]);
