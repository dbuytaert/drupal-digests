<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-supportawaresectionstorageinterface-to-layout-builder-3574461.php';

return RectorConfig::configure()
    ->withRules([AddSupportAwareSectionStorageInterfaceRector::class]);
