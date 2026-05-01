<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-modulehandlerinterface-writecache-and-3442009.php';

return RectorConfig::configure()
    ->withRules([RemoveModuleHandlerDeprecatedMethodsRector::class]);
