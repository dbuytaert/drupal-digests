<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/2987159
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.3, block_content_query_entity_reference_alter() was
// deprecated. Custom entity reference selection plugins that target
// block_content entities and extend DefaultSelection directly must now
// extend BlockContentSelection instead. BlockContentSelection handles
// the reusable-block filter internally, so the automatic hook behavior
// (and its deprecation warning) no longer applies.
//
// Before:
//   use Drupal\Core\Entity\Attribute\EntityReferenceSelection;
//   use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
//   use Drupal\Core\StringTranslation\TranslatableMarkup;
//   
//   #[EntityReferenceSelection(
//     id: "mymodule:block_content",
//     label: new TranslatableMarkup("My block content selection"),
//     group: "mymodule",
//     entity_types: ["block_content"],
//   )]
//   class MyBlockContentSelection extends DefaultSelection {
//   }
//
// After:
//   use Drupal\Core\Entity\Attribute\EntityReferenceSelection;
//   use Drupal\Core\StringTranslation\TranslatableMarkup;
//   use Drupal\block_content\Plugin\EntityReferenceSelection\BlockContentSelection;
//   
//   #[EntityReferenceSelection(
//     id: "mymodule:block_content",
//     label: new TranslatableMarkup("My block content selection"),
//     group: "mymodule",
//     entity_types: ["block_content"],
//   )]
//   class MyBlockContentSelection extends BlockContentSelection {
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Changes entity reference selection plugins for block_content that extend
 * DefaultSelection to extend BlockContentSelection instead, eliminating
 * reliance on the deprecated automatic reusable-block filtering in
 * block_content_query_entity_reference_alter().
 */
final class BlockContentSelectionExtendsRector extends AbstractRector
{
    private const DEFAULT_SELECTION_CLASS = 'Drupal\\Core\\Entity\\Plugin\\EntityReferenceSelection\\DefaultSelection';
    private const BLOCK_CONTENT_SELECTION_CLASS = 'Drupal\\block_content\\Plugin\\EntityReferenceSelection\\BlockContentSelection';
    private const ENTITY_REFERENCE_SELECTION_ATTR = 'Drupal\\Core\\Entity\\Attribute\\EntityReferenceSelection';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Entity reference selection plugins for block_content that extend DefaultSelection must extend BlockContentSelection instead, to avoid the deprecated automatic reusable-block filtering hook.',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Entity\Attribute\EntityReferenceSelection;
use Drupal\Core\Entity\Plugin\EntityReferenceSelection\DefaultSelection;
use Drupal\Core\StringTranslation\TranslatableMarkup;

#[EntityReferenceSelection(
  id: "mymodule:block_content",
  label: new TranslatableMarkup("My block content selection"),
  group: "mymodule",
  entity_types: ["block_content"],
)]
class MyBlockContentSelection extends DefaultSelection {
}
CODE,
                    <<<'CODE'
use Drupal\Core\Entity\Attribute\EntityReferenceSelection;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\block_content\Plugin\EntityReferenceSelection\BlockContentSelection;

#[EntityReferenceSelection(
  id: "mymodule:block_content",
  label: new TranslatableMarkup("My block content selection"),
  group: "mymodule",
  entity_types: ["block_content"],
)]
class MyBlockContentSelection extends BlockContentSelection {
}
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
        // Must have a parent class.
        if ($node->extends === null) {
            return null;
        }

        $parentName = $node->extends->toString();
        // Match either the short name "DefaultSelection" (resolved via use-statement)
        // or the fully-qualified class name.
        if ($parentName !== 'DefaultSelection'
            && $parentName !== self::DEFAULT_SELECTION_CLASS
        ) {
            return null;
        }

        // Skip the canonical BlockContentSelection class itself (it extends
        // DefaultSelection by design).
        $className = $this->getName($node);
        if ($className === 'BlockContentSelection'
            || $className === self::BLOCK_CONTENT_SELECTION_CLASS
        ) {
            return null;
        }

        // Check for the EntityReferenceSelection PHP attribute with
        // entity_types containing "block_content".
        if (!$this->hasBlockContentEntityReferenceSelectionAttribute($node)) {
            return null;
        }

        // Rewrite the parent class to BlockContentSelection.
        $node->extends = new Name\FullyQualified(self::BLOCK_CONTENT_SELECTION_CLASS);
        return $node;
    }

    private function hasBlockContentEntityReferenceSelectionAttribute(Class_ $class): bool
    {
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $attr->name->toString();
                // Match both short and fully-qualified attribute name.
                if ($attrName !== 'EntityReferenceSelection'
                    && $attrName !== self::ENTITY_REFERENCE_SELECTION_ATTR
                ) {
                    continue;
                }
                // Scan the attribute arguments for entity_types containing "block_content".
                foreach ($attr->args as $arg) {
                    // Named argument: entity_types: [...]
                    if ($arg->name === null || $arg->name->toString() !== 'entity_types') {
                        continue;
                    }
                    if (!$arg->value instanceof \PhpParser\Node\Expr\Array_) {
                        continue;
                    }
                    foreach ($arg->value->items as $item) {
                        if ($item === null) {
                            continue;
                        }
                        if ($item->value instanceof \PhpParser\Node\Scalar\String_
                            && $item->value->value === 'block_content'
                        ) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}
