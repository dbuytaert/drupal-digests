<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-language-module-procedural-functions-3574727.php';

return RectorConfig::configure()
    ->withRules([LanguageModuleFunctionDeprecationsRector::class]);
