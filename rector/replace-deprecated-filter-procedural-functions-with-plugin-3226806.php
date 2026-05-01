<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-filter-procedural-functions-with-plugin-3226806.php';

return RectorConfig::configure()
    ->withRules([DeprecatedFilterFunctionsRector::class]);
