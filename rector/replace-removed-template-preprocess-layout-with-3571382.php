<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-template-preprocess-layout-with-3571382.php';

return RectorConfig::configure()
    ->withRules([ReplaceTemplatePreprossLayoutRector::class]);
