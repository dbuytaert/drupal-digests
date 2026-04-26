<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-module-handler-and-entity-type-manager-3544308.php';

return RectorConfig::configure()
    ->withRules([CommentLinkBuilderConstructorRector::class]);
