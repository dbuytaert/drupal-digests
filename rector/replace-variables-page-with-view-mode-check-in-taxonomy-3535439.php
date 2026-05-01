<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-variables-page-with-view-mode-check-in-taxonomy-3535439.php';

return RectorConfig::configure()
    ->withRules([TaxonomyTermPageVariableToViewModeRector::class]);
