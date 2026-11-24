<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-form-select-options-with-formselectoptions-process-2448545.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([ReplaceFormSelectOptionsRector::class]);
