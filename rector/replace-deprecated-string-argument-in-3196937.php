<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-string-argument-in-3196937.php';

return RectorConfig::configure()
    ->withRules([BlockContentTestBaseStringToArrayRector::class]);
