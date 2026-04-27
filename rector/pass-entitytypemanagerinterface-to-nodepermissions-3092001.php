<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/pass-entitytypemanagerinterface-to-nodepermissions-3092001.php';

return RectorConfig::configure()
    ->withRules([PassEntityTypeManagerToNodePermissionsRector::class]);
