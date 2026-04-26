<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3439369
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The protected method getMigrationPluginManager() on
// Drupal\migrate\Plugin\migrate\id_map\Sql was deprecated in
// drupal:9.5.0 and removed in drupal:11.0.0. Subclasses that called
// $this->getMigrationPluginManager() must instead access the
// $this->migrationPluginManager property directly. The unrelated
// Migration::getMigrationPluginManager() is intentionally excluded.
//
// Before:
//   $manager = $this->getMigrationPluginManager();
//
// After:
//   $manager = $this->migrationPluginManager;


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated Sql::getMigrationPluginManager() with property access.
 *
 * The protected method getMigrationPluginManager() was deprecated in
 * drupal:9.5.0 and removed in drupal:11.0.0. Subclasses of Sql that called
 * this method should access $this->migrationPluginManager directly.
 *
 * Note: Migration::getMigrationPluginManager() is NOT deprecated and is
 * intentionally excluded from this transformation.
 *
 * @see https://www.drupal.org/node/3277306
 */
final class MigrateSqlGetMigrationPluginManagerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Sql::getMigrationPluginManager() calls with direct property access $this->migrationPluginManager.',
            [
                new CodeSample(
                    '$manager = $this->getMigrationPluginManager();',
                    '$manager = $this->migrationPluginManager;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        // Only target $this->getMigrationPluginManager() with no arguments.
        if (!$node->var instanceof Variable) {
            return null;
        }
        if ($node->var->name !== 'this') {
            return null;
        }
        if ($this->getName($node->name) !== 'getMigrationPluginManager') {
            return null;
        }
        if ($node->args !== []) {
            return null;
        }

        // Skip Migration::getMigrationPluginManager() which is NOT deprecated.
        // Only the Sql (id_map) version was deprecated and removed in 11.0.0.
        if ($this->isObjectType($node->var, new ObjectType('Drupal\migrate\Plugin\Migration'))) {
            return null;
        }

        // Replace with $this->migrationPluginManager property fetch.
        return new PropertyFetch(
            new Variable('this'),
            'migrationPluginManager'
        );
    }
}
