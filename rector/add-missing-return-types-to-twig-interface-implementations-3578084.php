<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-missing-return-types-to-twig-interface-implementations-3578084.php';

return RectorConfig::configure()
    ->withRules([AddTwigImplementationReturnTypesRector::class]);
