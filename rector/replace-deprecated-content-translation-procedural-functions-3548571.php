<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-content-translation-procedural-functions-3548571.php';

return RectorConfig::configure()
    ->withRules([ContentTranslationAdminFunctionsRector::class]);
