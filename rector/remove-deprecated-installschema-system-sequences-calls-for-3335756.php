<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-installschema-system-sequences-calls-for-3335756.php';

return RectorConfig::configure()
    ->withRules([RemoveInstallSchemaSystemSequencesRector::class]);
