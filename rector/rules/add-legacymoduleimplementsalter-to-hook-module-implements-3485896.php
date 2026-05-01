<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Adds the #[LegacyModuleImplementsAlter] attribute to any procedural
 * hook_module_implements_alter() function. Without this attribute, the
 * implementation triggers a deprecation notice in drupal:11.2.0 and is
 * removed in drupal:12.0.0. The attribute lets the legacy hook coexist
 * with the new attribute-based ordering system (Order, OrderAfter,
 * OrderBefore, RemoveHook, ReOrderHook) introduced in the same release.
 *
 * Before:
 *   function mymodule_module_implements_alter(array &$implementations, string $hook): void {
 *     // reorder implementations
 *   }
 *
 * After:
 *   #[\Drupal\Core\Hook\Attribute\LegacyModuleImplementsAlter]
 *   function mymodule_module_implements_alter(array &$implementations, string $hook): void {
 *     // reorder implementations
 *   }
 *
 * Caveats:
 *   This rule only adds the #[LegacyModuleImplementsAlter] BC shim
 *   attribute; it does not convert the hook body to the new attribute-
 *   based ordering (OrderAfter, OrderBefore, RemoveHook, ReOrderHook).
 *   That rewrite requires understanding module-specific business logic
 *   and must be done manually.
 *
 * @see https://www.drupal.org/node/3485896
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Function_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddLegacyModuleImplementsAlterAttributeRector extends AbstractRector
{
    private const ATTRIBUTE_CLASS = 'Drupal\\Core\\Hook\\Attribute\\LegacyModuleImplementsAlter';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[LegacyModuleImplementsAlter] attribute to hook_module_implements_alter() implementations. Without this attribute the implementation is deprecated in drupal:11.2.0 and removed in drupal:12.0.0.',
            [new CodeSample(
                'function mymodule_module_implements_alter(array &$implementations, string $hook): void {}',
                '#[\\Drupal\\Core\\Hook\\Attribute\\LegacyModuleImplementsAlter]
function mymodule_module_implements_alter(array &$implementations, string $hook): void {}',
            )],
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
        $name = $this->getName($node->name);
        if ($name === null || !str_ends_with($name, '_module_implements_alter')) {
            return null;
        }

        // Already has the attribute — skip.
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($this->getName($attr->name) === self::ATTRIBUTE_CLASS) {
                    return null;
                }
            }
        }

        $node->attrGroups[] = new AttributeGroup([
            new Attribute(new FullyQualified(self::ATTRIBUTE_CLASS)),
        ]);

        return $node;
    }
}
