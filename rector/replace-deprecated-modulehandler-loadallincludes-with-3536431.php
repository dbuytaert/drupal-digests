<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-modulehandler-loadallincludes-with-3536431.php';

return RectorConfig::configure()
    ->withRules([LoadAllIncludesRector::class]);
