<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-views-ui-admin-inc-functions-with-3035340.php';

return RectorConfig::configure()
    ->withRules([ViewsUiAdminDeprecatedFunctionsRector::class]);
