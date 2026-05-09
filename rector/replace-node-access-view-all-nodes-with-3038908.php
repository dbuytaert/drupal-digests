<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-node-access-view-all-nodes-with-3038908.php';

return RectorConfig::configure()
    ->withRules([NodeAccessViewAllNodesRector::class]);
