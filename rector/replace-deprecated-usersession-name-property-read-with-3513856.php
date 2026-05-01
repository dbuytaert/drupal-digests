<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-usersession-name-property-read-with-3513856.php';

return RectorConfig::configure()
    ->withRules([UserSessionNamePropertyToGetAccountNameRector::class]);
