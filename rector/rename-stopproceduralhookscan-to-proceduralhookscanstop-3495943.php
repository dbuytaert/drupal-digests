<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-stopproceduralhookscan-to-proceduralhookscanstop-3495943.php';

return RectorConfig::configure()
    ->withRules([RenameStopProceduralHookScanRector::class]);
