<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-error-currenterrorhandler-with-get-error-handler-3526515.php';

return RectorConfig::configure()
    ->withRules([ErrorCurrentErrorHandlerRector::class]);
