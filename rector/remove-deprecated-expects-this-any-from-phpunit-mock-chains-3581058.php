<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-expects-this-any-from-phpunit-mock-chains-3581058.php';

return RectorConfig::configure()
    ->withRules([RemoveExpectsAnyRector::class]);
