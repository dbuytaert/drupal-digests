<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-sessionmanager-delete-with-3577376.php';

return RectorConfig::configure()
    ->withRules([ReplaceSessionManagerDeleteRector::class]);
