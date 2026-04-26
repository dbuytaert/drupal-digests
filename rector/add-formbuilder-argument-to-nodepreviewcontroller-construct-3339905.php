<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-formbuilder-argument-to-nodepreviewcontroller-construct-3339905.php';

return RectorConfig::configure()
    ->withRules([NodePreviewControllerConstructorRector::class]);
