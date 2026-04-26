<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-commentmanagerinterface-3543035.php';

return RectorConfig::configure()
    ->withRules([CommentManagerGetCountNewCommentsRector::class]);
