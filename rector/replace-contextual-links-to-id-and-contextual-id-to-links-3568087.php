<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-contextual-links-to-id-and-contextual-id-to-links-3568087.php';

return RectorConfig::configure()
    ->withRules([ReplaceContextualProceduralFunctionsRector::class]);
