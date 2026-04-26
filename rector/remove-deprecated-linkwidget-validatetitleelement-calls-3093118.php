<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-linkwidget-validatetitleelement-calls-3093118.php';

return RectorConfig::configure()
    ->withRules([RemoveLinkWidgetValidateTitleElementRector::class]);
