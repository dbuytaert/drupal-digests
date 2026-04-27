<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-source-module-from-migratesource-php-attributes-3009349.php';

return RectorConfig::configure()
    ->withRules([RemoveSourceModuleFromMigrateSourceAttributeRector::class]);
