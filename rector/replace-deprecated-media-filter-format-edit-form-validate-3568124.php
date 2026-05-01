<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-media-filter-format-edit-form-validate-3568124.php';

return RectorConfig::configure()
    ->withRules([MediaFilterFormatEditFormValidateRector::class]);
