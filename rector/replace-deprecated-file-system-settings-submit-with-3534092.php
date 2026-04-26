<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-file-system-settings-submit-with-3534092.php';

return RectorConfig::configure()
    ->withRules([FileSystemSettingsSubmitRector::class]);
