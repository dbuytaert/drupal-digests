<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-removed-requirement-constants-with-3575841.php';

return RectorConfig::configure()
    ->withRules([ReplaceRequirementConstantsRector::class]);
