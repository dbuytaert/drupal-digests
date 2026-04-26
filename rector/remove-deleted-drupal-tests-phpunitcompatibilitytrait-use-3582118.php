<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deleted-drupal-tests-phpunitcompatibilitytrait-use-3582118.php';

return RectorConfig::configure()
    ->withRules([RemovePhpUnitCompatibilityTraitRector::class]);
