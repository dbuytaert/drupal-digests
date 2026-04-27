<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3009349
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.2 the source_module property was removed from the
// MigrateSource attribute class constructor (issue #3009349). Any source
// plugin that still passes source_module as a named argument to
// #[MigrateSource] will trigger a PHP error. This rule removes the
// invalid named argument from the attribute so plugins continue to load
// correctly.
//
// Before:
//   #[MigrateSource(
//     id: 'my_source',
//     source_module: 'my_module',
//   )]
//   class MySource extends SourcePluginBase {}
//
// After:
//   #[MigrateSource(
//     id: 'my_source',
//   )]
//   class MySource extends SourcePluginBase {}


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the source_module named argument from #[MigrateSource] PHP attributes.
 *
 * In Drupal 11.2 the source_module property was removed from the MigrateSource
 * attribute class constructor (issue #3009349). Any source plugin that still
 * passes source_module as a named argument to #[MigrateSource] will throw a
 * PHP error.
 */
final class RemoveSourceModuleFromMigrateSourceAttributeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the source_module named argument from #[MigrateSource] PHP attributes; the property was removed from the attribute class in Drupal 11.2 (issue #3009349).',
            [
                new CodeSample(
                    <<<'CODE'
#[MigrateSource(
  id: 'my_source',
  source_module: 'my_module',
)]
class MySource extends SourcePluginBase {}
CODE,
                    <<<'CODE'
#[MigrateSource(
  id: 'my_source',
)]
class MySource extends SourcePluginBase {}
CODE
                ),
            ]
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
                $attrName = $this->getName($attr->name);
                // Match both short name and fully-qualified name.
                if ($attrName !== 'MigrateSource'
                    && $attrName !== 'Drupal\\migrate\\Attribute\\MigrateSource'
                ) {
                    continue;
                }

                foreach ($attr->args as $key => $arg) {
                    if ($arg->name !== null && $this->getName($arg->name) === 'source_module') {
                        unset($attr->args[$key]);
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
