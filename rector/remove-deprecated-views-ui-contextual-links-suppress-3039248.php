<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-views-ui-contextual-links-suppress-3039248.php';

return RectorConfig::configure()
    ->withRules([RemoveViewsUiContextualLinksSuppressRector::class]);
