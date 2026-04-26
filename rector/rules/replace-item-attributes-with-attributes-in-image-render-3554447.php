<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3554447
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated #item_attributes key with #attributes in
// render arrays that use the image_formatter or
// responsive_image_formatter theme hooks. Drupal 11.4 standardised these
// hooks to use #attributes like all other render elements, deprecating
// the old #item_attributes property.
//
// Before:
//   $element = [
//       '#theme' => 'image_formatter',
//       '#item' => $item,
//       '#item_attributes' => ['class' => ['my-image']],
//       '#url' => $url,
//   ];
//
// After:
//   $element = [
//       '#theme' => 'image_formatter',
//       '#item' => $item,
//       '#attributes' => ['class' => ['my-image']],
//       '#url' => $url,
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\Config\RectorConfig;

/**
 * Replaces '#item_attributes' with '#attributes' in render arrays that use
 * the 'image_formatter' or 'responsive_image_formatter' theme hooks.
 */
final class ReplaceItemAttributesWithAttributesRector extends AbstractRector
{
    private const AFFECTED_THEME_HOOKS = [
        'image_formatter',
        'responsive_image_formatter',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated #item_attributes with #attributes in image_formatter and responsive_image_formatter render arrays',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$element = [
    '#theme' => 'image_formatter',
    '#item' => $item,
    '#item_attributes' => ['class' => ['my-image']],
];
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$element = [
    '#theme' => 'image_formatter',
    '#item' => $item,
    '#attributes' => ['class' => ['my-image']],
];
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isImageFormatterArray($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }
            if (!$item->key instanceof String_) {
                continue;
            }
            if ($item->key->value === '#item_attributes') {
                $item->key = new String_('#attributes');
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    private function isImageFormatterArray(Array_ $array): bool
    {
        foreach ($array->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }
            if (!$item->key instanceof String_) {
                continue;
            }
            if ($item->key->value !== '#theme') {
                continue;
            }
            if (!$item->value instanceof String_) {
                continue;
            }
            if (in_array($item->value->value, self::AFFECTED_THEME_HOOKS, true)) {
                return true;
            }
        }
        return false;
    }
}
