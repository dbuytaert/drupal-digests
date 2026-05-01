<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-locale-settings-translation-path-config-3571593.php';

return RectorConfig::configure()
    ->withRules([ReplaceLocaleTranslationPathConfigRector::class]);
