<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/guard-getpropertydefinition-calls-where-argument-comes-from-3557372.php';

return RectorConfig::configure()
    ->withRules([GuardGetPropertyDefinitionNullArgRector::class]);
