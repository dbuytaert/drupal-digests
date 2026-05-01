<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-views-entity-field-label-with-3069442.php';

return RectorConfig::configure()
    ->withRules([ViewsEntityFieldLabelRector::class]);
