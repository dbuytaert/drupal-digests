<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-classresolver-viewsconfigupdater-class-with-service-3529274.php';

return RectorConfig::configure()
    ->withRules([ViewsConfigUpdaterClassResolverToServiceRector::class]);
