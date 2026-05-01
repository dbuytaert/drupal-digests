<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/rename-hook-requirements-alter-implementations-to-hook-3490846.php';

return RectorConfig::configure()
    ->withRules([HookRequirementsAlterRenameRector::class]);
