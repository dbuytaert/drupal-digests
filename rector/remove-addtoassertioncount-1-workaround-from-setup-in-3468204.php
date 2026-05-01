<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-addtoassertioncount-1-workaround-from-setup-in-3468204.php';

return RectorConfig::configure()
    ->withRules([RemoveAddToAssertionCountFromSetUpRector::class]);
