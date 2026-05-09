<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-node-access-grants-with-nodegrantshelper-2473041.php';

return RectorConfig::configure()
    ->withRules([NodeAccessGrantsFuncCallRector::class]);
