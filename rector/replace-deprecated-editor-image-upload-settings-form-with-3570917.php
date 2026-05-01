<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-editor-image-upload-settings-form-with-3570917.php';

return RectorConfig::configure()
    ->withRules([ReplaceEditorImageUploadSettingsFormRector::class]);
