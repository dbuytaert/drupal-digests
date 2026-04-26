<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3574717
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes the boolean $expand argument from
// MigrationInterface::getMigrationDependencies() calls. The parameter
// was deprecated in drupal:11.0.0 and removed in drupal:12.0.0 (issue
// #3574717). PHP silently ignores the extra argument at runtime, but the
// expand behaviour is gone, so the call is semantically wrong. Targets
// only receivers typed as MigrationInterface to avoid false positives.
//
// Before:
//   $deps = $migration->getMigrationDependencies(TRUE);
//
// After:
//   $deps = $migration->getMigrationDependencies();


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Strips the removed $expand argument from getMigrationDependencies() calls.
 *
 * The boolean $expand parameter was deprecated in drupal:11.0.0 and removed
 * in drupal:12.0.0 (issue #3574717). Passing any argument to the method now
 * has no effect; this rule removes it from all call sites on objects whose
 * type resolves to MigrationInterface or its implementations.
 */
final class RemoveMigrationDependenciesExpandArgRector extends AbstractRector
{
    private const MIGRATION_INTERFACE = 'Drupal\\migrate\\Plugin\\MigrationInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the removed $expand argument from getMigrationDependencies() calls',
            [
                new CodeSample(
                    '$migration->getMigrationDependencies(TRUE);',
                    '$migration->getMigrationDependencies();'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        if (!$this->isName($node->name, 'getMigrationDependencies')) {
            return null;
        }

        if (empty($node->args)) {
            return null;
        }

        $callerType = $this->getType($node->var);
        $migrationInterface = new ObjectType(self::MIGRATION_INTERFACE);

        if (!$migrationInterface->isSuperTypeOf($callerType)->yes()) {
            return null;
        }

        $node->args = [];
        return $node;
    }
}
