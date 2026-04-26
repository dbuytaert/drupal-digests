<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-jsonapi-filter-among-constants-with-3495600.php';

return RectorConfig::configure()
    ->withRules([ReplaceJsonApiFilterConstantsRector::class]);
