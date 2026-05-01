<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-constructor-to-symfony-constraint-subclasses-for-named-3555534.php';

return RectorConfig::configure()
    ->withRules([AddSymfonyConstraintConstructorRector::class]);
