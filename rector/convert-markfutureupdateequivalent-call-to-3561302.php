<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/convert-markfutureupdateequivalent-call-to-3561302.php';

return RectorConfig::configure()
    ->withFileExtensions(['php', 'engine', 'inc', 'install', 'module', 'profile', 'theme'])
    ->withRules([ConvertMarkFutureUpdateEquivalentCallToAttributeRector::class]);
