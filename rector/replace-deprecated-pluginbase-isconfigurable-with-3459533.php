<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-pluginbase-isconfigurable-with-3459533.php';

return RectorConfig::configure()
    ->withRules([PluginBaseIsConfigurableRector::class]);
