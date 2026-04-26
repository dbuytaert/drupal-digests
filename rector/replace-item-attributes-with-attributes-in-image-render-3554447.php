<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-item-attributes-with-attributes-in-image-render-3554447.php';

return RectorConfig::configure()
    ->withRules([ReplaceItemAttributesWithAttributesRector::class]);
