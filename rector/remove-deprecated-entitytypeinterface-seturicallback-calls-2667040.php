<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-entitytypeinterface-seturicallback-calls-2667040.php';

return RectorConfig::configure()
    ->withRules([RemoveSetUriCallbackRector::class]);
