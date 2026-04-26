<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-cachebackendinterface-invalidateall-with-3498947.php';

return RectorConfig::configure()
    ->withRules([CacheInvalidateAllToDeleteAllRector::class]);
