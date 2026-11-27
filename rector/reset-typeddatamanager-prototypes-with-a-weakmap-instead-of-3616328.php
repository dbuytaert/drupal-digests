<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/reset-typeddatamanager-prototypes-with-a-weakmap-instead-of-3616328.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([ResetTypedDataPrototypesWithWeakMapRector::class]);
