<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-route-attributes-to-controller-methods-from-routing-yaml-3311365.php';

return RectorConfig::configure()
    ->withRules([YamlRoutesToRouteAttributeRector::class]);
