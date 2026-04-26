<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-tostringtrait-with-direct-tostring-method-3548957.php';

return RectorConfig::configure()
    ->withRules([RemoveDrupalToStringTraitRector::class]);
