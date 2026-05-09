<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-entitytypeinterface-getoriginalclass-with-3557461.php';

return RectorConfig::configure()
    ->withRules([GetOriginalClassToGetDecoratedClassesRector::class]);
