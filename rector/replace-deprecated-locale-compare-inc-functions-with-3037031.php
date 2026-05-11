<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-locale-compare-inc-functions-with-3037031.php';

return RectorConfig::configure()
    ->withRules([LocaleCompareIncToServiceRector::class]);
