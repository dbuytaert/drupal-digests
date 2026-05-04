<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-integer-access-values-with-booleans-in-render-arrays-3526250.php';

return RectorConfig::configure()
    ->withRules([ReplaceNonBoolAccessRector::class]);
