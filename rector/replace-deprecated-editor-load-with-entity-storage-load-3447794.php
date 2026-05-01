<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-editor-load-with-entity-storage-load-3447794.php';

return RectorConfig::configure()
    ->withRules([EditorLoadDeprecationRector::class]);
