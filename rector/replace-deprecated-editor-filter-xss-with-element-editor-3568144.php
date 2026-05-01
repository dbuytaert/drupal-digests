<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-editor-filter-xss-with-element-editor-3568144.php';

return RectorConfig::configure()
    ->withRules([EditorFilterXssRector::class]);
