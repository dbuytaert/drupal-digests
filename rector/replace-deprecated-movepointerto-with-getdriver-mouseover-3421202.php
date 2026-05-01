<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-movepointerto-with-getdriver-mouseover-3421202.php';

return RectorConfig::configure()
    ->withRules([MovePointerToMouseOverRector::class]);
