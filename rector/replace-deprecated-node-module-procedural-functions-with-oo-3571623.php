<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-node-module-procedural-functions-with-oo-3571623.php';

return RectorConfig::configure()
    ->withRules([ReplaceDeprecatedNodeFunctionsRector::class]);
