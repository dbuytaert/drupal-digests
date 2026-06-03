<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-yamlcachecollector-arg-to-librarydiscoveryparser-and-3486503.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddYamlCacheCollectorArgRector::class]);
