<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-toolkit-argument-from-3559481.php';

return RectorConfig::configure()
    ->withRules([RemoveImageToolkitOperationToolkitArgumentRector::class]);
