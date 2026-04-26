<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-update-manager-function-calls-with-no-3521059.php';

return RectorConfig::configure()
    ->withRules([RemoveDeprecatedUpdateManagerFuncCallsRector::class]);
