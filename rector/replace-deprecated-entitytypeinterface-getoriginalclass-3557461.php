<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-entitytypeinterface-getoriginalclass-3557461.php';

return RectorConfig::configure()
    ->withRules([GetOriginalClassToGetDecoratedClassesRector::class]);
