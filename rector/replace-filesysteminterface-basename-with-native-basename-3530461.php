<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-filesysteminterface-basename-with-native-basename-3530461.php';

return RectorConfig::configure()
    ->withRules([FileSystemBasenameToNativeRector::class]);
