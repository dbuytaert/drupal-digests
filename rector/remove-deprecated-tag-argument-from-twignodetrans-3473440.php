<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-tag-argument-from-twignodetrans-3473440.php';

return RectorConfig::configure()
    ->withRules([RemoveTwigNodeTransTagArgumentRector::class]);
