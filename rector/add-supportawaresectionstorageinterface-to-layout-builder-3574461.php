<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-supportawaresectionstorageinterface-to-layout-builder-3574461.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddSupportAwareSectionStorageInterfaceRector::class]);
