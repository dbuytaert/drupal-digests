<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-constraintviolation-type-hints-with-3578694.php';

return RectorConfig::configure()
    ->withRules([ConstraintViolationToInterfaceRector::class]);
