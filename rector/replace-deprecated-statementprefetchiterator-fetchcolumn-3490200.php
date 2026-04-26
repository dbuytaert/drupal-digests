<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-statementprefetchiterator-fetchcolumn-3490200.php';

return RectorConfig::configure()
    ->withRules([StatementPrefetchIteratorFetchColumnRector::class]);
