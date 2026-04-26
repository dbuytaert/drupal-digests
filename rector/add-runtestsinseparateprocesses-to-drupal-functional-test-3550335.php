<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-runtestsinseparateprocesses-to-drupal-functional-test-3550335.php';

return RectorConfig::configure()
    ->withRules([AddRunTestsInSeparateProcessesAttributeRector::class]);
