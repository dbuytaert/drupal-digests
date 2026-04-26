<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-entity-type-integer-id-helpers-with-3566801.php';

return RectorConfig::configure()
    ->withRules([UseEntityTypeHasIntegerIdRector::class]);
