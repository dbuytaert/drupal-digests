<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-resourceresponsevalidator-namespace-from-jsonapi-to-3472008.php';

return RectorConfig::configure()
    ->withRules([RenameResourceResponseValidatorRector::class]);
