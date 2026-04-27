<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-comment-uri-with-comment-permalink-2010202.php';

return RectorConfig::configure()
    ->withRules([CommentUriToPermalinkRector::class]);
