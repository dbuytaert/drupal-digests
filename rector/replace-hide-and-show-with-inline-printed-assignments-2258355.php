<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-hide-and-show-with-inline-printed-assignments-2258355.php';

return RectorConfig::configure()
    ->withRules([HideShowToInlinePrintedRector::class]);
