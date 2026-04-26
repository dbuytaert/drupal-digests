<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

require_once __DIR__ . '/rules/replace-deprecated-reciperunner-installmodule-with-3498026.php';

return RectorConfig::configure()
    ->withRules([RecipeRunnerInstallModuleRector::class]);
