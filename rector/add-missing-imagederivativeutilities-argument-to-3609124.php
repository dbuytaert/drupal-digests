<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-missing-imagederivativeutilities-argument-to-3609124.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddImageDerivativeUtilitiesToImageFormatterRector::class]);
