<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-locale-translation-default-server-3477277.php';

return RectorConfig::configure()
    ->withRules([LocaleTranslationDefaultServerPatternRector::class]);
