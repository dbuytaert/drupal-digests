<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-aliaswhitelist-aliaswhitelistinterface-3151086.php';

return RectorConfig::configure()
    ->withRules([RenamePathAliasWhitelistRebuildRector::class]);
