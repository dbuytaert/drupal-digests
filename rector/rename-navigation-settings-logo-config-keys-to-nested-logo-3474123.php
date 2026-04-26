<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-navigation-settings-logo-config-keys-to-nested-logo-3474123.php';

return RectorConfig::configure()
    ->withRules([NavigationSettingsLogoKeysRector::class]);
