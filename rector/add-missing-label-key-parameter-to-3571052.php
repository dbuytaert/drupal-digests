<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-missing-label-key-parameter-to-3571052.php';

return RectorConfig::configure()
    ->withRules([AddLabelKeyParamToCategorizingPluginManagerRector::class]);
