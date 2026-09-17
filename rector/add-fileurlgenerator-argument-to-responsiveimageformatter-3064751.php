<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-fileurlgenerator-argument-to-responsiveimageformatter-3064751.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddFileUrlGeneratorToResponsiveImageFormatterRector::class]);
