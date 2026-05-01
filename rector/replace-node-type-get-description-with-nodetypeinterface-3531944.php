<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-node-type-get-description-with-nodetypeinterface-3531944.php';

return RectorConfig::configure()
    ->withRules([NodeTypeGetDescriptionRector::class]);
