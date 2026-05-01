<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-datetimerangeconstantsinterface-constants-3574901.php';

return RectorConfig::configure()
    ->withRules([ReplaceDatetimeDeprecatedApisRector::class]);
