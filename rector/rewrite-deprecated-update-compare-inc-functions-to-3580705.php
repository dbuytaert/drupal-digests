<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rewrite-deprecated-update-compare-inc-functions-to-3580705.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([UpdateCompareFunctionsToServiceRector::class]);
