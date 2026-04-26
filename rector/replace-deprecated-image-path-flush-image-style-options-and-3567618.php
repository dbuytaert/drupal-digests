<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-image-path-flush-image-style-options-and-3567618.php';

return RectorConfig::configure()
    ->withRules([DeprecatedImageFunctionsRector::class]);
