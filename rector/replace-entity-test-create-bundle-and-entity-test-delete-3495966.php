<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-entity-test-create-bundle-and-entity-test-delete-3495966.php';

return RectorConfig::configure()
    ->withRules([EntityTestBundleFunctionsRector::class]);
