<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-object-type-hint-to-execute-in-executableinterface-3570920.php';

return RectorConfig::configure()
    ->withRules([AddObjectTypeToExecuteMethodRector::class]);
