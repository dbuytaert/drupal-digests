<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-file-get-content-headers-with-fileinterface-3494126.php';

return RectorConfig::configure()
    ->withRules([FileGetContentHeadersRector::class]);
