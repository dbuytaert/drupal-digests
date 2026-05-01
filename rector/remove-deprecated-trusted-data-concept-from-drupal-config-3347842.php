<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/remove-deprecated-trusted-data-concept-from-drupal-config-3347842.php';

return RectorConfig::configure()
    ->withRules([RemoveTrustedDataConceptRector::class]);
