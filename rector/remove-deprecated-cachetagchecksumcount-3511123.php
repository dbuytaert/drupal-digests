<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-cachetagchecksumcount-3511123.php';

return RectorConfig::configure()
    ->withRules([RemoveCacheTagChecksumAssertionsRector::class]);
