<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-menu-ui-module-procedural-functions-with-3571400.php';

return RectorConfig::configure()
    ->withRules([ReplaceDeprecatedMenuUiFunctionsRector::class]);
