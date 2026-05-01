<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-automated-cron-settings-submit-calls-and-3566768.php';

return RectorConfig::configure()
    ->withRules([RemoveAutomatedCronSettingsSubmitHandlerRector::class]);
