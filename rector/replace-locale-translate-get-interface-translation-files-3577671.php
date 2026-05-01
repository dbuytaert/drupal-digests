<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-locale-translate-get-interface-translation-files-3577671.php';

return RectorConfig::configure()
    ->withRules([LocaleTranslateGetInterfaceTranslationFilesRector::class]);
