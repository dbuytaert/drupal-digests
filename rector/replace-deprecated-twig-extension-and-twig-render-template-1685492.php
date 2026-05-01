<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-twig-extension-and-twig-render-template-1685492.php';

return RectorConfig::configure()
    ->withRules([TwigEngineFunctionsRector::class]);
