<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-empty-string-description-with-null-in-3448457.php';

return RectorConfig::configure()
    ->withRules([EntityFormModeEmptyDescriptionToNullRector::class]);
