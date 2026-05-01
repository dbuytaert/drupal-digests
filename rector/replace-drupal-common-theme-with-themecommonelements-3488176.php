<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-drupal-common-theme-with-themecommonelements-3488176.php';

return RectorConfig::configure()
    ->withRules([ReplaceDrupalCommonThemeRector::class]);
