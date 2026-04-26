<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-request-time-constant-with-drupal-time-3395986.php';

return RectorConfig::configure()
    ->withRules([ReplaceRequestTimeConstantRector::class]);
