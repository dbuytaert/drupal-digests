<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3564937
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes array items whose value is a call to
// CachePluginBase::getRowCacheKeys() or CachePluginBase::getRowId().
// Both methods are deprecated in drupal:11.4.0 and removed in
// drupal:13.0.0 with no replacement (see #3564958). The primary pattern
// is a 'keys' entry in a #cache render array for views rows; dropping it
// eliminates redundant per-row render caching that was identified as
// duplicative overhead.
//
// Before:
//   $data = [
//       '#cache' => [
//           'keys' => $cache_plugin->getRowCacheKeys($row),
//           'tags' => $cache_plugin->getRowCacheTags($row),
//           'max-age' => $max_age,
//       ],
//   ];
//
// After:
//   $data = [
//       '#cache' => [
//           'tags' => $cache_plugin->getRowCacheTags($row),
//           'max-age' => $max_age,
//       ],
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\Config\RectorConfig;

/**
 * Removes deprecated CachePluginBase::getRowCacheKeys() and getRowId() calls.
 *
 * Both methods are deprecated in drupal:11.4.0 and removed in drupal:13.0.0
 * with no replacement (https://www.drupal.org/node/3564958). The typical
 * usage is as the 'keys' value in a #cache render array for individual views
 * rows. Removing the array item stops Drupal from caching each row separately
 * in the render cache, which was identified as duplicative overhead.
 */
final class RemoveViewsRowCacheKeysRector extends AbstractRector
{
    /**
     * Method names deprecated with no replacement.
     *
     * @var string[]
     */
    private const DEPRECATED_METHODS = ['getRowCacheKeys', 'getRowId'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated CachePluginBase::getRowCacheKeys() and getRowId() array item values. Both are deprecated in drupal:11.4.0, removed in drupal:13.0.0 with no replacement.',
            [
                new CodeSample(
                    <<<'CODE_BEFORE'
$data = [
    '#cache' => [
        'keys' => $cache_plugin->getRowCacheKeys($row),
        'tags' => $cache_plugin->getRowCacheTags($row),
        'max-age' => $max_age,
    ],
];
CODE_BEFORE,
                    <<<'CODE_AFTER'
$data = [
    '#cache' => [
        'tags' => $cache_plugin->getRowCacheTags($row),
        'max-age' => $max_age,
    ],
];
CODE_AFTER
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /**
     * @param Array_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $modified = false;
        $newItems = [];

        foreach ($node->items as $item) {
            if (!$item instanceof ArrayItem) {
                $newItems[] = $item;
                continue;
            }

            // Remove any array item whose value is a call to a deprecated method.
            if ($item->value instanceof MethodCall
                && $this->isDeprecatedMethodCall($item->value)
            ) {
                $modified = true;
                continue;
            }

            $newItems[] = $item;
        }

        if (!$modified) {
            return null;
        }

        $node->items = $newItems;
        return $node;
    }

    private function isDeprecatedMethodCall(MethodCall $call): bool
    {
        if (!$call->name instanceof Identifier) {
            return false;
        }
        return in_array($call->name->toString(), self::DEPRECATED_METHODS, true);
    }
}
