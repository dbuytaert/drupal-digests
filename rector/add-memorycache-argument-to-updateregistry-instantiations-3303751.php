<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-memorycache-argument-to-updateregistry-instantiations-3303751.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddUpdateRegistryMemoryCacheArgumentRector::class]);
