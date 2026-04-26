<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-librarydiscovery-class-with-3571057.php';

return RectorConfig::configure()
    ->withRules([ReplaceLibraryDiscoveryClassRector::class]);
