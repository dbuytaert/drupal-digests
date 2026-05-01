<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-node-mass-update-with-nodebulkupdate-service-3533083.php';

return RectorConfig::configure()
    ->withRules([NodeMassUpdateToNodeBulkUpdateRector::class]);
