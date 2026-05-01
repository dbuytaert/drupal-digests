<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-attribute-class-to-defaultpluginmanager-constructor-3265945.php';

return RectorConfig::configure()
    ->withRules([AddPluginManagerAttributeClassRector::class]);
