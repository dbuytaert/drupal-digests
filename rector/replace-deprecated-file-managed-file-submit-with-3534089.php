<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-file-managed-file-submit-with-3534089.php';

return RectorConfig::configure()
    ->withRules([FileManagedFileSubmitRector::class]);
