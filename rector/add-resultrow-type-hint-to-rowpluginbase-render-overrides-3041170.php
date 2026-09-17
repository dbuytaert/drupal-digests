<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-resultrow-type-hint-to-rowpluginbase-render-overrides-3041170.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([AddResultRowTypeHintToRowPluginRenderRector::class]);
