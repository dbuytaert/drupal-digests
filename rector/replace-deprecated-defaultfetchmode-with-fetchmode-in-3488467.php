<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-defaultfetchmode-with-fetchmode-in-3488467.php';

return RectorConfig::configure()
    ->withRules([ReplaceDefaultFetchModeWithFetchModeRector::class]);
