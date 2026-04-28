<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-array-type-hint-to-by-reference-parameters-in-hook-3579922.php';

return RectorConfig::configure()
    ->withRules([AddArrayTypeToHookParametersRector::class]);
