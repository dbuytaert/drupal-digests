<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/inline-the-deprecated-formbuilderinterface-htmx-request-3555916.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([InlineFormBuilderHtmxRequestConstantRector::class]);
