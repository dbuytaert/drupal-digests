<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-hook-ranking-to-hook-node-search-ranking-1019966.php';

return RectorConfig::configure()
    ->withRules([RenameHookRankingRector::class]);
