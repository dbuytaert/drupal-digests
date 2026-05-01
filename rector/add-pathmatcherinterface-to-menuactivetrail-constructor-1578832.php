<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-pathmatcherinterface-to-menuactivetrail-constructor-1578832.php';

return RectorConfig::configure()
    ->withRules([MenuActiveTrailPathMatcherRector::class]);
