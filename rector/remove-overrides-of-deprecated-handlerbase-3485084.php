<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-overrides-of-deprecated-handlerbase-3485084.php';

return RectorConfig::configure()
    ->withRules([RemoveDefineExtraOptionsOverrideRector::class]);
