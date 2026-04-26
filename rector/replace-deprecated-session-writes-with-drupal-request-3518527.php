<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-session-writes-with-drupal-request-3518527.php';

return RectorConfig::configure()
    ->withRules([SessionSuperGlobalToRequestSessionRector::class]);
