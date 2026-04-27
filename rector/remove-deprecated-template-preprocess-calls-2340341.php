<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-template-preprocess-calls-2340341.php';

return RectorConfig::configure()
    ->withRules([RemoveTemplatePreprocessCallRector::class]);
