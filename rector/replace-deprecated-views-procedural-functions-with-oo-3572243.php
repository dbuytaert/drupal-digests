<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-views-procedural-functions-with-oo-3572243.php';

return RectorConfig::configure()
    ->withRules([ReplaceDeprecatedViewsFunctionsRector::class]);
