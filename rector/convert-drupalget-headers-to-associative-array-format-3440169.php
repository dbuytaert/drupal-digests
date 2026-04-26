<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/convert-drupalget-headers-to-associative-array-format-3440169.php';

return RectorConfig::configure()
    ->withRules([DrupalGetHeadersAssocArrayRector::class]);
