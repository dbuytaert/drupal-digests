<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-template-preprocess-calls-with-service-3501136.php';

return RectorConfig::configure()
    ->withRules([TemplatePreprocessToServiceRector::class]);
