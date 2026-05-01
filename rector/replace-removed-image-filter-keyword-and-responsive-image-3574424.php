<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-image-filter-keyword-and-responsive-image-3574424.php';

return RectorConfig::configure()
    ->withRules([ReplaceRemovedImageResponsiveImageFunctionsRector::class]);
