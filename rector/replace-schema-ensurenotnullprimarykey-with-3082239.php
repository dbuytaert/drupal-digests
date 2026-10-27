<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([EnsureNotNullPrimaryKeyToValidatePrimaryKeySchemaRector::class]);
