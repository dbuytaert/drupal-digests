<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-nodeviewcontroller-with-entityviewcontroller-3589630.php';

return RectorConfig::configure()
    ->withRules([ReplaceNodeViewControllerRector::class]);
