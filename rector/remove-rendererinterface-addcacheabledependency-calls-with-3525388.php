<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-rendererinterface-addcacheabledependency-calls-with-3525388.php';

return RectorConfig::configure()
    ->withRules([RemoveRendererAddCacheableDependencyNonObjectRector::class]);
