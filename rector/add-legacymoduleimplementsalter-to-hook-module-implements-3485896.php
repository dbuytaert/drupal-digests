<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-legacymoduleimplementsalter-to-hook-module-implements-3485896.php';

return RectorConfig::configure()
    ->withRules([AddLegacyModuleImplementsAlterAttributeRector::class]);
