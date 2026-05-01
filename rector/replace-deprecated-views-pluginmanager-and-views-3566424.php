<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-views-pluginmanager-and-views-3566424.php';

return RectorConfig::configure()
    ->withRules([ViewsPluginHandlerManagerRector::class]);
