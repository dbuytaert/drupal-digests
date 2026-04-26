<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-modulehandlerinterface-addmodule-and-3528899.php';

return RectorConfig::configure()
    ->withRules([RemoveModuleHandlerAddModuleCallsRector::class]);
