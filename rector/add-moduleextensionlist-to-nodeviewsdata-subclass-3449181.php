<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-moduleextensionlist-to-nodeviewsdata-subclass-3449181.php';

return RectorConfig::configure()
    ->withRules([AddModuleExtensionListToNodeViewsDataConstructRector::class]);
