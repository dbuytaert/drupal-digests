<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-helpsearch-to-searchhelpsearch-in-search-help-sub-3581109.php';

return RectorConfig::configure()
    ->withRules([RenameHelpSearchToSearchHelpSearchRector::class]);
