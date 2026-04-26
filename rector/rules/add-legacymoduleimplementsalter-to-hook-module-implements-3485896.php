<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3485896
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecates procedural hook_module_implements_alter()
// implementations that lack the #[LegacyModuleImplementsAlter]
// attribute; the hook is removed in 12.0. Adding the attribute is the
// required BC-safe first step: Drupal 11.2+ will skip execution during
// attribute-based ordering while older Drupal versions continue running
// it normally. This rule adds the missing attribute automatically across
// module files.
//
// Before:
//   function mymodule_module_implements_alter(&$implementations, $hook): void {
//     // reorder implementations
//   }
//
// After:
//   #[\Drupal\Core\Hook\Attribute\LegacyModuleImplementsAlter]
//   function mymodule_module_implements_alter(&$implementations, $hook): void {
//     // reorder implementations
//   }


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Function_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds #[LegacyModuleImplementsAlter] to hook_module_implements_alter functions.
 *
 * Any procedural implementation of hook_module_implements_alter() without the
 * #[LegacyModuleImplementsAlter] attribute is deprecated in Drupal 11.2.0 and
 * removed in 12.0.0. Adding the attribute opts the function back in for legacy
 * execution while attribute-based ordering is being adopted.
 */
final class AddLegacyModuleImplementsAlterAttributeRector extends AbstractRector
{
    private const ATTRIBUTE_CLASS = 'Drupal\Core\Hook\Attribute\LegacyModuleImplementsAlter';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[LegacyModuleImplementsAlter] to hook_module_implements_alter() implementations that are missing it.',
            [
                new CodeSample(
                    'function mymodule_module_implements_alter(&$implementations, $hook): void {}',
                    'use Drupal\Core\Hook\Attribute\LegacyModuleImplementsAlter;

#[LegacyModuleImplementsAlter]
function mymodule_module_implements_alter(&$implementations, $hook): void {}'
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
        $name = $this->getName($node);
        if ($name === null) {
            return null;
        }

        // Must be named *_module_implements_alter (but not the hook template).
        if (!str_ends_with($name, '_module_implements_alter') || $name === 'hook_module_implements_alter') {
            return null;
        }

        // Check if #[LegacyModuleImplementsAlter] is already present.
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($this->isName($attr->name, self::ATTRIBUTE_CLASS)) {
                    return null;
                }
            }
        }

        // Add the attribute.
        $node->attrGroups[] = new AttributeGroup([
            new Attribute(new FullyQualified(self::ATTRIBUTE_CLASS)),
        ]);

        return $node;
    }
}
