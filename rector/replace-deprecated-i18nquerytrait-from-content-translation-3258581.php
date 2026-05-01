<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-i18nquerytrait-from-content-translation-3258581.php';

return RectorConfig::configure()
    ->withRules([RenameI18nQueryTraitRector::class]);
