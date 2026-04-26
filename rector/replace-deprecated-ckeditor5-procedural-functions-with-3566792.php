<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-ckeditor5-procedural-functions-with-3566792.php';

return RectorConfig::configure()
    ->withRules([ReplaceCkeditor5ProceduralFunctionsRector::class]);
