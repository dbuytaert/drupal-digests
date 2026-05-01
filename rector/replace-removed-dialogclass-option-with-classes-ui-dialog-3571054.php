<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-dialogclass-option-with-classes-ui-dialog-3571054.php';

return RectorConfig::configure()
    ->withRules([ReplaceDialogClassOptionRector::class]);
