<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-views-field-default-views-data-and-views-3489415.php';

return RectorConfig::configure()
    ->withRules([ViewsFieldDefaultViewsDataRector::class]);
