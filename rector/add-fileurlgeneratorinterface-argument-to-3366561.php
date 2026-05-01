<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/add-fileurlgeneratorinterface-argument-to-3366561.php';

return RectorConfig::configure()
    ->withRules([AddFileUrlGeneratorToAttachmentsProcessorRector::class]);
