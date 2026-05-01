<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-number-alphadecimaltoint-null-calls-with-3442810.php';

return RectorConfig::configure()
    ->withRules([AlphadecimalToIntNullOrEmptyRector::class]);
