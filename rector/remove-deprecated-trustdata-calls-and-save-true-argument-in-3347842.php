<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-trustdata-calls-and-save-true-argument-in-3347842.php';

return RectorConfig::configure()
    ->withRules([RemoveTrustDataCallRector::class]);
