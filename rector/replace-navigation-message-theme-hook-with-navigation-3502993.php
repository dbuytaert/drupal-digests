<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-navigation-message-theme-hook-with-navigation-3502993.php';

return RectorConfig::configure()
    ->withRules([NavigationMessageThemeToComponentRector::class]);
