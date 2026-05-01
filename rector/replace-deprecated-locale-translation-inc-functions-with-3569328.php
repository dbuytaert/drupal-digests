<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-locale-translation-inc-functions-with-3569328.php';

return RectorConfig::configure()
    ->withRules([ReplaceLocaleTranslationIncFunctionsRector::class]);
