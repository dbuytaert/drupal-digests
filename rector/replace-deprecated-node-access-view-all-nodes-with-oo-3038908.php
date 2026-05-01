<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-node-access-view-all-nodes-with-oo-3038908.php';

return RectorConfig::configure()
    ->withRules([NodeAccessViewAllNodesRector::class]);
