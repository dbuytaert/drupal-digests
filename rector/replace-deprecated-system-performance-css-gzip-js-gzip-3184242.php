<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-system-performance-css-gzip-js-gzip-3184242.php';

return RectorConfig::configure()
    ->withRules([SystemPerformanceGzipToCompressRector::class]);
