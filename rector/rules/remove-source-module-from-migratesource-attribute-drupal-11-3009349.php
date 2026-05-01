<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * The source_module constructor parameter was removed from
 * Drupal\migrate\Attribute\MigrateSource in drupal:11.2.0 (issue
 * #3009349). Using #[MigrateSource(source_module: '...')] now produces a
 * PHP error at plugin discovery time. This rule removes the
 * source_module named argument from all #[MigrateSource] usages.
 *
 * Before:
 *   #[MigrateSource(
 *     id: 'd7_node',
 *     source_module: 'node',
 *   )]
 *   class Node extends DrupalSqlBase {}
 *
 * After:
 *   #[MigrateSource(
 *     id: 'd7_node',
 *   )]
 *   class Node extends DrupalSqlBase {}
 *
 * Caveats:
 *   For classes extending DrupalSqlBase, source_module must still be
 *   declared somewhere so that MigrationPluginManager can enforce it
 *   for Drupal 6/7 migrations. After removing it from the attribute,
 *   define it either via the @MigrateSource annotation (converting from
 *   attribute to annotation) or via the source_module key in the
 *   migration YAML configuration. The conversion from attribute syntax
 *   to annotation syntax is not automated by this rule.
 *
 * @see https://www.drupal.org/node/3009349
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveSourceModuleFromMigrateSourceAttributeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the source_module named argument from #[MigrateSource] attribute usages, as it was removed from the attribute class in drupal:11.2.0.',
            [new CodeSample(
                <<<'CODE'
#[MigrateSource(
  id: 'd7_node',
  source_module: 'node',
)]
class Node extends DrupalSqlBase {}
CODE,
                <<<'CODE'
#[MigrateSource(
  id: 'd7_node',
)]
class Node extends DrupalSqlBase {}
CODE,
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        $changed = false;

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if (!$this->isName($attr->name, 'Drupal\\migrate\\Attribute\\MigrateSource')) {
                    continue;
                }

                foreach ($attr->args as $key => $arg) {
                    if ($arg->name !== null && $arg->name->name === 'source_module') {
                        unset($attr->args[$key]);
                        // Re-index to avoid gaps.
                        $attr->args = array_values($attr->args);
                        $changed = true;
                        break;
                    }
                }
            }
        }

        return $changed ? $node : null;
    }
}
