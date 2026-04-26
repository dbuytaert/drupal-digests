<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3576556
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes cacheExpire() method overrides from classes that extend
// CachePluginBase (or known subclasses Time/Tag). The method was
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0 with no
// replacement (issue #3576556). Cache expiration is now configured
// exclusively via cacheSetMaxAge(), making any cacheExpire() override
// dead code that triggers a deprecation warning.
//
// Before:
//   use Drupal\views\Plugin\views\cache\CachePluginBase;
//   
//   class MyCache extends CachePluginBase {
//     protected function cacheExpire($type) {
//       return \Drupal::time()->getRequestTime() - $this->options['lifespan'];
//     }
//     protected function cacheSetMaxAge($type) {
//       return $this->options['lifespan'] ?: \Drupal\Core\Cache\Cache::PERMANENT;
//     }
//   }
//
// After:
//   use Drupal\views\Plugin\views\cache\CachePluginBase;
//   
//   class MyCache extends CachePluginBase {
//     protected function cacheSetMaxAge($type) {
//       return $this->options['lifespan'] ?: \Drupal\Core\Cache\Cache::PERMANENT;
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes overridden cacheExpire() methods from CachePluginBase subclasses.
 *
 * CachePluginBase::cacheExpire() was deprecated in drupal:11.4.0 and removed
 * in drupal:13.0.0 with no replacement (issue #3576556). Subclasses that
 * override this method should remove the override; cache expiration is now
 * configured via cacheSetMaxAge() which returns a max-age integer.
 */
final class RemoveCacheExpireOverrideRector extends AbstractRector
{
    private const CACHE_PLUGIN_BASE_FQCN = 'Drupal\\views\\Plugin\\views\\cache\\CachePluginBase';

    /**
     * Known parent class short names used in views cache plugins.
     *
     * Covers the base class plus core subclasses Time and Tag, which are
     * themselves frequent parents for contrib cache plugins.
     */
    private const PARENT_SHORT_NAMES = [
        'CachePluginBase',
        'Time',
        'Tag',
        'None',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated cacheExpire() overrides from Views CachePluginBase subclasses',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\cache\CachePluginBase;

class MyCache extends CachePluginBase {
  protected function cacheExpire($type) {
    return \Drupal::time()->getRequestTime() - $this->options['lifespan'];
  }
  protected function cacheSetMaxAge($type) {
    return $this->options['lifespan'] ?: \Drupal\Core\Cache\Cache::PERMANENT;
  }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\cache\CachePluginBase;

class MyCache extends CachePluginBase {
  protected function cacheSetMaxAge($type) {
    return $this->options['lifespan'] ?: \Drupal\Core\Cache\Cache::PERMANENT;
  }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        if (!$this->isCachePluginBaseSubclass($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->stmts as $key => $stmt) {
            if ($stmt instanceof ClassMethod && $this->isName($stmt, 'cacheExpire')) {
                unset($node->stmts[$key]);
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    private function isCachePluginBaseSubclass(Class_ $node): bool
    {
        if ($node->extends === null) {
            return false;
        }

        $parentName = $node->extends->toString();

        // Match the fully-qualified class name.
        if ($parentName === self::CACHE_PLUGIN_BASE_FQCN) {
            return true;
        }

        // Match known short names and namespace-relative names (works when the
        // file uses `use` imports, which is standard Drupal plugin style).
        foreach (self::PARENT_SHORT_NAMES as $short) {
            if ($parentName === $short || str_ends_with($parentName, '\\' . $short)) {
                return true;
            }
        }

        // PHPStan-based type resolution for classes that extend intermediate
        // subclasses not listed above.
        try {
            $objectType = new \PHPStan\Type\ObjectType(self::CACHE_PLUGIN_BASE_FQCN);
            $extendsType = new \PHPStan\Type\ObjectType($parentName);
            if ($objectType->isSuperTypeOf($extendsType)->yes()) {
                return true;
            }
        } catch (\Throwable) {
            // Type resolution may be unavailable in some project setups.
        }

        return false;
    }
}
