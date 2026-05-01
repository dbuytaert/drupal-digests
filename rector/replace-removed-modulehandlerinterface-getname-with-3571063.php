<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-modulehandlerinterface-getname-with-3571063.php';

return RectorConfig::configure()
    ->withRules([ReplaceModuleHandlerGetNameRector::class]);
