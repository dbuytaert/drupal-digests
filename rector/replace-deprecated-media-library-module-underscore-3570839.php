<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-media-library-module-underscore-3570839.php';

return RectorConfig::configure()
    ->withRules([ReplaceDeprecatedMediaLibraryFunctionsRector::class]);
