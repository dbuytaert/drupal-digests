<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-filesysteminterface-exists-constants-with-3575575.php';

return RectorConfig::configure()
    ->withRules([ReplaceFileExistsConstantsRector::class]);
