<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3584766
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds native return type declarations to methods in classes that
// implement CacheTagsChecksumInterface (getCurrentChecksum(): string,
// isValid(): bool, reset(): void) or CacheTagsInvalidatorInterface
// (invalidateTags(): void). Drupal core added these types to
// CacheTagsChecksumTrait in issue #3584766 to eliminate Symfony
// DebugClassLoader deprecation notices. Classes that implement the
// interfaces directly (e.g. decorator or proxy classes) must declare the
// same types on their own method overrides.
//
// Before:
//   use Drupal\Core\Cache\CacheTagsChecksumInterface;
//   
//   class MyChecksum implements CacheTagsChecksumInterface {
//     public function getCurrentChecksum(array $tags) { return '0'; }
//     public function isValid($checksum, array $tags) { return true; }
//     public function reset() {}
//   }
//
// After:
//   use Drupal\Core\Cache\CacheTagsChecksumInterface;
//   
//   class MyChecksum implements CacheTagsChecksumInterface {
//     public function getCurrentChecksum(array $tags): string { return '0'; }
//     public function isValid($checksum, array $tags): bool { return true; }
//     public function reset(): void {}
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds native return types to CacheTagsChecksumInterface and
 * CacheTagsInvalidatorInterface implementations.
 *
 * Drupal core added native return types to CacheTagsChecksumTrait in issue
 * #3584766 to suppress Symfony DebugClassLoader deprecation notices:
 *   Method "CacheTagsChecksumInterface::getCurrentChecksum()" might add
 *   "string" as a native return type declaration in the future.
 *
 * Classes that implement these interfaces directly without using the trait
 * (e.g. decorator / proxy classes) must also declare the return types on
 * their own method overrides. The methods covered and their required types:
 *   getCurrentChecksum(array $tags): string   (CacheTagsChecksumInterface)
 *   isValid($checksum, array $tags): bool     (CacheTagsChecksumInterface)
 *   reset(): void                             (CacheTagsChecksumInterface)
 *   invalidateTags(array $tags): void         (CacheTagsInvalidatorInterface)
 */
final class AddCacheTagsChecksumReturnTypesRector extends AbstractRector
{
    private const CHECKSUM_INTERFACE   = 'Drupal\\Core\\Cache\\CacheTagsChecksumInterface';
    private const INVALIDATOR_INTERFACE = 'Drupal\\Core\\Cache\\CacheTagsInvalidatorInterface';

    /** Methods declared on CacheTagsChecksumInterface => required return type. */
    private const CHECKSUM_METHOD_TYPES = [
        'getCurrentChecksum' => 'string',
        'isValid'            => 'bool',
        'reset'              => 'void',
    ];

    /** Methods declared on CacheTagsInvalidatorInterface => required return type. */
    private const INVALIDATOR_METHOD_TYPES = [
        'invalidateTags' => 'void',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing native return types to CacheTagsChecksumInterface and CacheTagsInvalidatorInterface implementations',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Cache\CacheTagsChecksumInterface;

class MyChecksum implements CacheTagsChecksumInterface {
  public function getCurrentChecksum(array $tags) { return '0'; }
  public function isValid($checksum, array $tags) { return true; }
  public function reset() {}
}
CODE
                    ,
                    <<<'CODE'
use Drupal\Core\Cache\CacheTagsChecksumInterface;

class MyChecksum implements CacheTagsChecksumInterface {
  public function getCurrentChecksum(array $tags): string { return '0'; }
  public function isValid($checksum, array $tags): bool { return true; }
  public function reset(): void {}
}
CODE
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

        $hasChecksum   = $this->classImplementsInterface($node, self::CHECKSUM_INTERFACE);
        $hasInvalidator = $this->classImplementsInterface($node, self::INVALIDATOR_INTERFACE);

        if (!$hasChecksum && !$hasInvalidator) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            // Skip methods that already have a return type.
            if ($method->returnType !== null) {
                continue;
            }

            $methodName = $this->getName($method);
            if ($methodName === null) {
                continue;
            }

            $returnTypeStr = null;
            if ($hasChecksum && isset(self::CHECKSUM_METHOD_TYPES[$methodName])) {
                $returnTypeStr = self::CHECKSUM_METHOD_TYPES[$methodName];
            } elseif ($hasInvalidator && isset(self::INVALIDATOR_METHOD_TYPES[$methodName])) {
                $returnTypeStr = self::INVALIDATOR_METHOD_TYPES[$methodName];
            }

            if ($returnTypeStr === null) {
                continue;
            }

            $method->returnType = new Identifier($returnTypeStr);
            $changed = true;
        }

        return $changed ? $node : null;
    }

    /**
     * Returns true when the class declares the given interface in its
     * `implements` list, matched by FQCN, short name, or a name ending with
     * the short name (handles `use`-imported names).
     */
    private function classImplementsInterface(Class_ $class, string $fqcn): bool
    {
        $shortName = substr($fqcn, (int) strrpos($fqcn, '\\') + 1);

        foreach ($class->implements as $implement) {
            $name = $implement->toString();
            if (
                $name === $fqcn
                || $name === $shortName
                || str_ends_with($name, '\\' . $shortName)
            ) {
                return true;
            }
        }

        return false;
    }
}
