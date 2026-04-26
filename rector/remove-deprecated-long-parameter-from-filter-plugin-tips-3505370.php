<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-long-parameter-from-filter-plugin-tips-3505370.php';

return RectorConfig::configure()
    ->withRules([RemoveFilterTipsLongParamRector::class]);
