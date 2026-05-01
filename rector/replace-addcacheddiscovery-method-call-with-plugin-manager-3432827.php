<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-addcacheddiscovery-method-call-with-plugin-manager-3432827.php';

return RectorConfig::configure()
    ->withRules([ReplaceAddCachedDiscoveryMethodCallRector::class]);
