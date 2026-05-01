<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-block-content-add-body-field-calls-3535526.php';

return RectorConfig::configure()
    ->withRules([RemoveBlockContentAddBodyFieldRector::class]);
