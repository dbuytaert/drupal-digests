<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-native-return-types-to-cachetagschecksuminterface-and-3584766.php';

return RectorConfig::configure()
    ->withRules([AddCacheTagsChecksumReturnTypesRector::class]);
