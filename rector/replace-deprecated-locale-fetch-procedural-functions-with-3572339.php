<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-locale-fetch-procedural-functions-with-3572339.php';

return RectorConfig::configure()
    ->withRules([ReplaceLocaleFetchFunctionsRector::class]);
