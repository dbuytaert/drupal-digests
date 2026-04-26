<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-commentiteminterface-and-3574661.php';

return RectorConfig::configure()
    ->withRules([ReplaceCommentDeprecatedConstantsRector::class]);
