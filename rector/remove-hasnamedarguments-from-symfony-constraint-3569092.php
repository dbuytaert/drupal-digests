<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-hasnamedarguments-from-symfony-constraint-3569092.php';

return RectorConfig::configure()
    ->withRules([RemoveHasNamedArgumentsAttributeRector::class]);
