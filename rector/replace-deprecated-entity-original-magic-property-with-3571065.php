<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-entity-original-magic-property-with-3571065.php';

return RectorConfig::configure()
    ->withRules([EntityOriginalPropertyToMethodRector::class]);
