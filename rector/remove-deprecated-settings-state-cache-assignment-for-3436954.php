<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-settings-state-cache-assignment-for-3436954.php';

return RectorConfig::configure()
    ->withRules([RemoveStateCacheSettingRector::class]);
