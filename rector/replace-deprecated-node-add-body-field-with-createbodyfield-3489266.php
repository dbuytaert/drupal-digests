<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-node-add-body-field-with-createbodyfield-3489266.php';

return RectorConfig::configure()
    ->withRules([NodeAddBodyFieldRector::class]);
