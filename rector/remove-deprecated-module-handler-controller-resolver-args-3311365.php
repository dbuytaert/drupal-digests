<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-module-handler-controller-resolver-args-3311365.php';

return RectorConfig::configure()
    ->withRules([RemoveRouteBuilderDeprecatedArgsRector::class]);
