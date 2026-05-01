<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-componentpluginmanager-to-themeinstaller-constructor-3522505.php';

return RectorConfig::configure()
    ->withRules([AddComponentPluginManagerToThemeInstallerRector::class]);
