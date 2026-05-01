<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-views-block-items-per-page-none-with-null-in-3520946.php';

return RectorConfig::configure()
    ->withRules([ViewsBlockItemsPerPageNoneToNullRector::class]);
