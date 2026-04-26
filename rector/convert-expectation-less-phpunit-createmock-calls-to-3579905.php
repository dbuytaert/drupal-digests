<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/convert-expectation-less-phpunit-createmock-calls-to-3579905.php';

return RectorConfig::configure()
    ->withRules([CreateMockToCreateStubRector::class]);
