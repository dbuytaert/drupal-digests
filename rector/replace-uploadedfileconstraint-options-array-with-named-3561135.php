<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-uploadedfileconstraint-options-array-with-named-3561135.php';

return RectorConfig::configure()
    ->withRules([UploadedFileConstraintArrayOptionsToNamedArgsRector::class]);
