<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-drupal-requirements-severity-with-3410938.php';

return RectorConfig::configure()
    ->withRules([DrupalRequirementsSeverityToEnumRector::class]);
