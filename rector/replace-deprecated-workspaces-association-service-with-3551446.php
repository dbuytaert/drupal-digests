<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-workspaces-association-service-with-3551446.php';

return RectorConfig::configure()
    ->withRules([WorkspacesAssociationToTrackerRector::class]);
