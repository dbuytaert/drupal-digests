<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-nodestorage-revisionids-and-3396062.php';

return RectorConfig::configure()
    ->withRules([NodeStorageDeprecatedMethodsRector::class]);
