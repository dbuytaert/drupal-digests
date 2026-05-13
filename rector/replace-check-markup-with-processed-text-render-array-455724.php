<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-check-markup-with-processed-text-render-array-455724.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([CheckMarkupToProcessedTextRector::class]);
