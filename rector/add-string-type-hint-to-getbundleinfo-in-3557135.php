<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-string-type-hint-to-getbundleinfo-in-3557135.php';

return RectorConfig::configure()
    ->withRules([AddStringTypeToGetBundleInfoRector::class]);
