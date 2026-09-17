<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-yaml-cache-collector-argument-to-local-action-task-3593485.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddYamlCacheCollectorArgumentToMenuManagersRector::class]);
