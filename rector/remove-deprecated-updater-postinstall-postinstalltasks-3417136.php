<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-updater-postinstall-postinstalltasks-3417136.php';

return RectorConfig::configure()
    ->withRules([RemoveUpdaterPostInstallMethodsRector::class]);
