<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-getdrupalroot-with-this-root-in-drupal-test-classes-3589047.php';

return RectorConfig::configure()
    ->withRules([GetDrupalRootToRootPropertyRector::class]);
