<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3502993
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The navigation__message theme hook was removed from Drupal's
// Navigation module and replaced by the navigation:message Single
// Directory Component. This rule rewrites render arrays using #theme =>
// 'navigation__message' to the equivalent #type => 'component' /
// #component => 'navigation:message' structure, including unwrapping
// ['#markup' => $expr] content values to (string) $expr plain-string
// props.
//
// Before:
//   $element = [
//       '#theme' => 'navigation__message',
//       '#content' => ['#markup' => $text],
//       '#url' => $url,
//       '#type' => 'warning',
//   ];
//
// After:
//   $element = [
//       '#type' => 'component',
//       '#component' => 'navigation:message',
//       '#props' => [
//           'type' => 'warning',
//           'url' => $url,
//           'content' => (string) $text,
//       ],
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts render arrays using the removed navigation__message theme hook
 * to the navigation:message SDC component introduced in Drupal 11.x.
 */
final class NavigationMessageThemeToSDCRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace #theme => navigation__message render arrays with the navigation:message SDC component',
            [
                new CodeSample(
                    <<<'CODE'
$element = [
    '#theme' => 'navigation__message',
    '#content' => ['#markup' => $text],
    '#url' => $url,
    '#type' => 'warning',
];
CODE,
                    <<<'CODE'
$element = [
    '#type' => 'component',
    '#component' => 'navigation:message',
    '#props' => [
        'type' => 'warning',
        'url' => $url,
        'content' => (string) $text,
    ],
];
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /** @param Array_ $node */
    public function refactor(Node $node): ?Node
    {
        $themeFound = false;
        $contentItem = null;
        $urlItem = null;
        $typeItem = null;

        foreach ($node->items as $item) {
            if (!$item instanceof ArrayItem || !$item->key instanceof String_) {
                continue;
            }
            $key = $item->key->value;

            if ($key === '#theme') {
                if ($item->value instanceof String_ && $item->value->value === 'navigation__message') {
                    $themeFound = true;
                }
            } elseif ($key === '#content') {
                $contentItem = $item;
            } elseif ($key === '#url') {
                $urlItem = $item;
            } elseif ($key === '#type') {
                $typeItem = $item;
            }
        }

        if (!$themeFound) {
            return null;
        }

        // Build #props array items.
        $propsItems = [];

        // type prop (message type: status/warning/error).
        if ($typeItem !== null) {
            $propsItems[] = new ArrayItem($typeItem->value, new String_('type'));
        }

        // url prop.
        if ($urlItem !== null) {
            $propsItems[] = new ArrayItem($urlItem->value, new String_('url'));
        }

        // content prop: unwrap ['#markup' => $expr] render arrays to (string) $expr.
        if ($contentItem !== null) {
            $contentValue = $contentItem->value;
            if ($contentValue instanceof Array_) {
                $markupValue = $this->extractMarkupValue($contentValue);
                if ($markupValue !== null) {
                    $contentValue = new CastString_($markupValue);
                }
            }
            $propsItems[] = new ArrayItem($contentValue, new String_('content'));
        }

        // Replace the array items with the SDC component structure.
        $node->items = [
            new ArrayItem(new String_('component'), new String_('#type')),
            new ArrayItem(new String_('navigation:message'), new String_('#component')),
            new ArrayItem(new Array_($propsItems), new String_('#props')),
        ];

        return $node;
    }

    /**
     * Extracts the value of '#markup' from a render array, if present.
     */
    private function extractMarkupValue(Array_ $array): ?Node\Expr
    {
        foreach ($array->items as $item) {
            if (!$item instanceof ArrayItem || !$item->key instanceof String_) {
                continue;
            }
            if ($item->key->value === '#markup') {
                return $item->value;
            }
        }
        return null;
    }
}
