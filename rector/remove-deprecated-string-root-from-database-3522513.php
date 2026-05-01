<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-string-root-from-database-3522513.php';

return RectorConfig::configure()
    ->withRules([RemoveRootFromConvertDbUrlToConnectionInfoRector::class]);
