<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-dblog-procedural-functions-with-3560398.php';

return RectorConfig::configure()
    ->withRules([ReplaceDblogProceduralFunctionsRector::class]);
