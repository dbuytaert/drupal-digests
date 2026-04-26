<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-hook-preprocess-block-content-add-list-to-hook-3346394.php';

return RectorConfig::configure()
    ->withRules([RenameBlockContentAddListPreprocessRector::class]);
