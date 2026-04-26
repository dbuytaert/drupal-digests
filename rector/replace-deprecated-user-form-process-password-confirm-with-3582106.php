<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-user-form-process-password-confirm-with-3582106.php';

return RectorConfig::configure()
    ->withRules([ReplaceUserFormProcessPasswordConfirmRector::class]);
