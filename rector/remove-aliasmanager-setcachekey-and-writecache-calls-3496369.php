<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-aliasmanager-setcachekey-and-writecache-calls-3496369.php';

return RectorConfig::configure()
    ->withRules([RemoveAliasManagerCacheMethodCallsRector::class]);
