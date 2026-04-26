<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-group-legacy-annotation-with-ignoredeprecations-3417066.php';

return RectorConfig::configure()
    ->withRules([GroupLegacyToIgnoreDeprecationsRector::class]);
