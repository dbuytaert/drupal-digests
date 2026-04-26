<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-field-ui-form-manage-field-form-submit-with-3567163.php';

return RectorConfig::configure()
    ->withRules([ReplaceFieldUiFormManageFieldFormSubmitRector::class]);
