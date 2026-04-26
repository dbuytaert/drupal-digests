<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3498947
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated
// CacheBackendInterface::invalidateAll() with deleteAll().
// invalidateAll() was deprecated in Drupal 11.2.0 and removed in 12.0.0
// because invalidating an entire cache bin is expensive for backends
// like Redis. deleteAll() is the correct replacement for clearing all
// entries in a bin.
//
// Before:
//   use Drupal\Core\Cache\CacheBackendInterface;
//   
//   function clear_cache(CacheBackendInterface $cache): void {
//       $cache->invalidateAll();
//   }
//
// After:
//   use Drupal\Core\Cache\CacheBackendInterface;
//   
//   function clear_cache(CacheBackendInterface $cache): void {
//       $cache->deleteAll();
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CacheInvalidateAllToDeleteAllRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated CacheBackendInterface::invalidateAll() calls with deleteAll()',
            [
                new CodeSample(
                    '$cache->invalidateAll();',
                    '$cache->deleteAll();'
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
        if (!$this->isName($node->name, 'invalidateAll')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Drupal\Core\Cache\CacheBackendInterface'))) {
            return null;
        }

        $node->name = new Identifier('deleteAll');

        return $node;
    }
}
