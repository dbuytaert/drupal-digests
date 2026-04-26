<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-locale-batch-helper-functions-with-their-3575254.php';

return RectorConfig::configure()
    ->withRules([ReplaceLocaleConfigBatchFunctionsRector::class]);
