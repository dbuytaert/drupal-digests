<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-theme-get-setting-and-system-default-3573896.php';

return RectorConfig::configure()
    ->withRules([ReplaceThemeGetSettingRector::class]);
