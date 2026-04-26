<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3490846
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Renames procedural {module}_requirements_alter() implementations to
// {module}_runtime_requirements_alter(). The old hook_requirements_alter
// is deprecated in drupal:11.3.0 and removed in drupal:13.0.0. The most
// common use case is altering status-report (runtime) requirements,
// which maps directly to the new hook_runtime_requirements_alter.
// Modules that also alter update-phase requirements must manually add a
// {module}_update_requirements_alter() function.
//
// Before:
//   function mymodule_requirements_alter(array &$requirements): void {
//     $requirements['php']['title'] = t('PHP version');
//   }
//
// After:
//   function mymodule_runtime_requirements_alter(array &$requirements): void {
//     $requirements['php']['title'] = t('PHP version');
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames hook_requirements_alter() implementations to
 * hook_runtime_requirements_alter() as the old hook is deprecated in
 * Drupal 11.3.0 and removed in 13.0.0.
 */
final class HookRequirementsAlterRenameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename {module}_requirements_alter() procedural hook implementations to {module}_runtime_requirements_alter() as required by the deprecation of hook_requirements_alter() in drupal:11.3.0.',
            [
                new CodeSample(
                    'function mymodule_requirements_alter(array &$requirements): void {
  $requirements[\'php\'][\'title\'] = t(\'PHP version\');
}',
                    'function mymodule_runtime_requirements_alter(array &$requirements): void {
  $requirements[\'php\'][\'title\'] = t(\'PHP version\');
}'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    /** @param Function_ $node */
    public function refactor(Node $node): ?Node
    {
        $name = $node->name->toString();

        // Match procedural implementations of hook_requirements_alter.
        // The pattern is {module_name}_requirements_alter.
        // Exclude the api documentation function itself.
        if ($name === 'hook_requirements_alter') {
            return null;
        }

        if (!str_ends_with($name, '_requirements_alter')) {
            return null;
        }

        // Must have exactly one parameter (the $requirements array by reference).
        if (count($node->params) !== 1) {
            return null;
        }

        $param = $node->params[0];

        // The parameter must be passed by reference (array &$requirements).
        if (!$param->byRef) {
            return null;
        }

        // Rename *_requirements_alter to *_runtime_requirements_alter.
        $prefix = substr($name, 0, -strlen('_requirements_alter'));
        $node->name = new Identifier($prefix . '_runtime_requirements_alter');

        return $node;
    }
}
