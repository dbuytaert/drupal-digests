<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-commentiteminterface-form-below-and-form-3550054.php';

return RectorConfig::configure()
    ->withRules([FormLocationRector::class]);
