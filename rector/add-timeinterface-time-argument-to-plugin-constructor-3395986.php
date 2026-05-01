<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-timeinterface-time-argument-to-plugin-constructor-3395986.php';

return RectorConfig::configure()
    ->withRules([AddTimeInterfaceToPluginConstructorsRector::class]);
