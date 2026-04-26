<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3511123
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecated PerformanceData::getCacheTagChecksumCount() and
// getCacheTagIsValidCount() with no replacement (removed in 12.0). The
// assertMetrics() helper dispatches these getters dynamically via array
// keys, so this rule removes the 'CacheTagChecksumCount' and
// 'CacheTagIsValidCount' entries from inline arrays passed to
// assertMetrics(), preventing false test failures caused by non-
// deterministic counts.
//
// Before:
//   $this->assertMetrics([
//       'CacheGetCount' => 5,
//       'CacheTagChecksumCount' => 38,
//       'CacheTagIsValidCount' => 43,
//       'CacheTagInvalidationCount' => 0,
//   ], $performance_data);
//
// After:
//   $this->assertMetrics([
//       'CacheGetCount' => 5,
//       'CacheTagInvalidationCount' => 0,
//   ], $performance_data);


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated CacheTagChecksumCount and CacheTagIsValidCount keys
 * from assertMetrics() calls in Drupal performance tests.
 *
 * @see https://www.drupal.org/node/3511149
 */
final class RemoveCacheTagChecksumAssertionsRector extends AbstractRector
{
    private const DEPRECATED_KEYS = [
        'CacheTagChecksumCount',
        'CacheTagIsValidCount',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated CacheTagChecksumCount and CacheTagIsValidCount entries from assertMetrics() calls (deprecated in drupal:11.2.0, removed in drupal:12.0.0, no replacement).',
            [
                new CodeSample(
                    <<<'CODE'
$this->assertMetrics([
    'CacheGetCount' => 5,
    'CacheTagChecksumCount' => 38,
    'CacheTagIsValidCount' => 43,
    'CacheTagInvalidationCount' => 0,
], $performance_data);
CODE,
                    <<<'CODE'
$this->assertMetrics([
    'CacheGetCount' => 5,
    'CacheTagInvalidationCount' => 0,
], $performance_data);
CODE
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
        if ($this->getName($node->name) !== 'assertMetrics') {
            return null;
        }

        if (!isset($node->args[0])) {
            return null;
        }

        $firstArg = $node->args[0]->value;
        if (!$firstArg instanceof Array_) {
            return null;
        }

        $changed = false;
        $newItems = [];
        foreach ($firstArg->items as $item) {
            if ($item === null) {
                $newItems[] = $item;
                continue;
            }
            $key = $item->key;
            if ($key instanceof String_ && in_array($key->value, self::DEPRECATED_KEYS, true)) {
                $changed = true;
                continue;
            }
            $newItems[] = $item;
        }

        if (!$changed) {
            return null;
        }

        $firstArg->items = $newItems;
        return $node;
    }
}
