<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-hide-and-show-with-printed-assignment-2258355.php';

return RectorConfig::configure()
    ->withRules([HideShowFunctionToHashPrintedRector::class]);
