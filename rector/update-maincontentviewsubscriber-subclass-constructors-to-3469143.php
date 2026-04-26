<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/update-maincontentviewsubscriber-subclass-constructors-to-3469143.php';

return RectorConfig::configure()
    ->withRules([UpdateMainContentViewSubscriberConstructorRector::class]);
