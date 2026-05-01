<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-field-purge-batch-with-fieldpurger-2907780.php';

return RectorConfig::configure()
    ->withRules([FieldPurgeBatchRector::class]);
