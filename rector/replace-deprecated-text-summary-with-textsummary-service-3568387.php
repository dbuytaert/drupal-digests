<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-text-summary-with-textsummary-service-3568387.php';

return RectorConfig::configure()
    ->withRules([TextSummaryFunctionToServiceRector::class]);
