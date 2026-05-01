<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-entityreferenceentityformatter-recursive-2940605.php';

return RectorConfig::configure()
    ->withRules([RemoveEntityReferenceRecursiveLimitConstantRector::class]);
