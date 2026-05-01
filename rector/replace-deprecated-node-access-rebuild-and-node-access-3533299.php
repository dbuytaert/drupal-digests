<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-node-access-rebuild-and-node-access-3533299.php';

return RectorConfig::configure()
    ->withRules([NodeAccessRebuildFunctionsRector::class]);
