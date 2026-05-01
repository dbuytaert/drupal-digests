<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-runtestsinseparateprocesses-to-concrete-drupal-kernel-3546029.php';

return RectorConfig::configure()
    ->withRules([AddRunTestsInSeparateProcessesAttributeRector::class]);
