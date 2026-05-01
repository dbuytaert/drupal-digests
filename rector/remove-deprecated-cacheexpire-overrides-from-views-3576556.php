<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-cacheexpire-overrides-from-views-3576556.php';

return RectorConfig::configure()
    ->withRules([RemoveCacheExpireOverrideRector::class]);
