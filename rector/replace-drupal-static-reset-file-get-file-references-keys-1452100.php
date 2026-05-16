<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-drupal-static-reset-file-get-file-references-keys-1452100.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([ReplaceDrupalStaticResetFileReferencesRector::class]);
