<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3502993
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The navigation__message theme hook was removed in Drupal 11.x when the
// Navigation module's message component was converted to a Single
// Directory Component. Any render array using '#theme' =>
// 'navigation__message' must be replaced with '#type' => 'component'
// targeting navigation:message. The rule also handles #content render
// arrays containing #markup by extracting the value with a (string)
// cast.
//
// Before:
//   $build = [
//       '#theme' => 'navigation__message',
//       '#content' => [
//           '#markup' => t('Demo message.'),
//       ],
//       '#url' => $url,
//       '#type' => 'warning',
//   ];
//
// After:
//   $build = [
//       '#type' => 'component',
//       '#component' => 'navigation:message',
//       '#props' => [
//           'type' => 'warning',
//           'url' => $url,
//           'content' => (string) t('Demo message.'),
//       ],
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Cast\String_ as StringCast;
use PhpParser\Node\Scalar\String_ as StringNode;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts '#theme' => 'navigation__message' render arrays to the
 * navigation:message SDC component introduced in Drupal 11.x.
 */
final class NavigationMessageThemeToComponentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace '#theme' => 'navigation__message' render arrays with '#type' => 'component' using the navigation:message SDC component.",
            [
                new CodeSample(
                    <<<'CODE'
$build = [
    '#theme' => 'navigation__message',
    '#content' => 'Demo message',
    '#url' => $url,
    '#type' => 'warning',
];
CODE,
                    <<<'CODE'
$build = [
    '#type' => 'component',
    '#component' => 'navigation:message',
    '#props' => [
        'type' => 'warning',
        'url' => $url,
        'content' => 'Demo message',
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
        $contentExpr = null;
        $urlExpr = null;
        $typeExpr = null;
        $otherItems = [];

        foreach ($node->items as $item) {
            if (!$item instanceof ArrayItem || $item->key === null) {
                $otherItems[] = $item;
                continue;
            }

            if (!$item->key instanceof StringNode) {
                $otherItems[] = $item;
                continue;
            }

            switch ($item->key->value) {
                case '#theme':
                    if (!$item->value instanceof StringNode || $item->value->value !== 'navigation__message') {
                        return null;
                    }
                    $themeFound = true;
                    break;

                case '#content':
                    $contentExpr = $item->value;
                    break;

                case '#url':
                    $urlExpr = $item->value;
                    break;

                case '#type':
                    $typeExpr = $item->value;
                    break;

                default:
                    $otherItems[] = $item;
                    break;
            }
        }

        if (!$themeFound) {
            return null;
        }

        // Build #props sub-array.
        $propsItems = [];

        // 'type' prop comes from old '#type' (e.g. 'warning', 'status', 'error').
        $propsItems[] = new ArrayItem(
            $typeExpr ?? new StringNode('status'),
            new StringNode('type')
        );

        // 'url' prop.
        if ($urlExpr !== null) {
            $propsItems[] = new ArrayItem($urlExpr, new StringNode('url'));
        }

        // 'content' prop: if the old value was a render array ['#markup' => $expr],
        // extract and cast to (string); otherwise use the expression directly.
        if ($contentExpr !== null) {
            $contentValue = $this->resolveContentExpr($contentExpr);
            $propsItems[] = new ArrayItem($contentValue, new StringNode('content'));
        }

        // Replace all items with the SDC component structure.
        $node->items = [
            new ArrayItem(new StringNode('component'), new StringNode('#type')),
            new ArrayItem(new StringNode('navigation:message'), new StringNode('#component')),
            new ArrayItem(new Array_($propsItems), new StringNode('#props')),
            ...$otherItems,
        ];

        return $node;
    }

    /**
     * Extracts the content value from either a direct expression or a
     * ['#markup' => $expr] render array, casting to (string) when needed.
     */
    private function resolveContentExpr(Node\Expr $expr): Node\Expr
    {
        if (!$expr instanceof Array_) {
            return $expr;
        }

        foreach ($expr->items as $item) {
            if (
                $item instanceof ArrayItem
                && $item->key instanceof StringNode
                && $item->key->value === '#markup'
            ) {
                return new StringCast($item->value);
            }
        }

        return $expr;
    }
}
