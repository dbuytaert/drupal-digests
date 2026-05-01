<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-constants-with-nodepreviewmode-enum-in-3538277.php';

return RectorConfig::configure()
    ->withRules([NodeSetPreviewModeRector::class]);
