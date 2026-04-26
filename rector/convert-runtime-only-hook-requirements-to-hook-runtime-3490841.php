<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/convert-runtime-only-hook-requirements-to-hook-runtime-3490841.php';

return RectorConfig::configure()
    ->withRules([ConvertHookRequirementsRuntimeRector::class]);
