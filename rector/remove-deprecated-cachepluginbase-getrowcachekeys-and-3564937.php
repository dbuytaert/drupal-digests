<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-cachepluginbase-getrowcachekeys-and-3564937.php';

return RectorConfig::configure()
    ->withRules([RemoveViewsRowCacheKeysRector::class]);
