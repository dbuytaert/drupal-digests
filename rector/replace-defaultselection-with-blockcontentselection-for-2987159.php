<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-defaultselection-with-blockcontentselection-for-2987159.php';

return RectorConfig::configure()
    ->withRules([BlockContentSelectionExtendsRector::class]);
