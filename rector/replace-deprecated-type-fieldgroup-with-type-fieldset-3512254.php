<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-type-fieldgroup-with-type-fieldset-3512254.php';

return RectorConfig::configure()
    ->withRules([FieldgroupToFieldsetRector::class]);
