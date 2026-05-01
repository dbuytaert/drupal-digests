<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-contact-module-procedural-submit-3566888.php';

return RectorConfig::configure()
    ->withRules([ReplaceContactDeprecatedFunctionsRector::class]);
