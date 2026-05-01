<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-expectdeprecation-with-phpunit-11-3550268.php';

return RectorConfig::configure()
    ->withRules([ReplaceExpectDeprecationRector::class]);
