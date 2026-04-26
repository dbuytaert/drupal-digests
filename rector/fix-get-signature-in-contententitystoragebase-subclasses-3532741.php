<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/fix-get-signature-in-contententitystoragebase-subclasses-3532741.php';

return RectorConfig::configure()
    ->withRules([FixContentEntityStorageBaseGetSignatureRector::class]);
