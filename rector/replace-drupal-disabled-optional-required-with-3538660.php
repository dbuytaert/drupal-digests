<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-drupal-disabled-optional-required-with-3538660.php';

return RectorConfig::configure()
    ->withRules([CommentPreviewModeRector::class]);
