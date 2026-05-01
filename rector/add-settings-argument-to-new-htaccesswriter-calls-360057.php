<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-settings-argument-to-new-htaccesswriter-calls-360057.php';

return RectorConfig::configure()
    ->withRules([HtaccessWriterSettingsArgumentRector::class]);
