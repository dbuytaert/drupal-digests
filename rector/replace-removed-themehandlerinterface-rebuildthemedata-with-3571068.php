<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-themehandlerinterface-rebuildthemedata-with-3571068.php';

return RectorConfig::configure()
    ->withRules([ReplaceRebuildThemeDataRector::class]);
