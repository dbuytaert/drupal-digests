<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-event-dispatcher-to-json-api-resourceobjectnormalizer-3100732.php';

return RectorConfig::configure()
    ->withRules([AddEventDispatcherToJsonApiConstructorRector::class]);
