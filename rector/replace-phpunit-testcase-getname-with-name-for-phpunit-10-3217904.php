<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-phpunit-testcase-getname-with-name-for-phpunit-10-3217904.php';

return RectorConfig::configure()
    ->withRules([GetNameToNameRector::class]);
