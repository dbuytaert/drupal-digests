<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3532741
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2.5 added public function __get(string $name): mixed to
// ContentEntityStorageBase to provide a deprecation warning for the
// removed $latestRevisionIds property. Any subclass that declares
// __get() without the string parameter type hint or the : mixed return
// type now causes a fatal PHP signature incompatibility error. This rule
// adds the missing type declarations to restore compatibility.
//
// Before:
//   class MyEntityStorage extends ContentEntityStorageBase {
//     public function __get($name) {
//       if ($name === 'latestRevisionIds') {
//         return [];
//       }
//       return $this->$name ?? NULL;
//     }
//   }
//
// After:
//   class MyEntityStorage extends ContentEntityStorageBase {
//     public function __get(string $name): mixed {
//       if ($name === 'latestRevisionIds') {
//         return [];
//       }
//       return $this->$name ?? NULL;
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Fixes __get() method signatures in subclasses of ContentEntityStorageBase.
 *
 * Drupal 11.2.5 added public function __get(string $name): mixed to
 * ContentEntityStorageBase. Subclasses that define __get() without the
 * 'string' type hint or 'mixed' return type produce a fatal PHP signature
 * incompatibility error.
 */
final class FixContentEntityStorageBaseGetSignatureRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Fix __get() method signature in classes extending ContentEntityStorageBase to be compatible with the parent\'s public function __get(string $name): mixed added in drupal:11.2.5.',
            [
                new CodeSample(
                    <<<'CODE'
class MyEntityStorage extends ContentEntityStorageBase {
  public function __get($name) {
    // custom logic
  }
}
CODE,
                    <<<'CODE'
class MyEntityStorage extends ContentEntityStorageBase {
  public function __get(string $name): mixed {
    // custom logic
  }
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
        // Only target classes that extend ContentEntityStorageBase.
        if (!$node->extends instanceof Name) {
            return null;
        }

        $parentClassName = $this->getName($node->extends);
        if ($parentClassName !== 'ContentEntityStorageBase'
            && !str_ends_with((string) $parentClassName, '\\ContentEntityStorageBase')
        ) {
            return null;
        }

        $changed = false;

        foreach ($node->getMethods() as $method) {
            if ($this->getName($method->name) !== '__get') {
                continue;
            }

            // Fix the $name parameter: add 'string' type if missing.
            if (isset($method->params[0]) && $method->params[0]->type === null) {
                $method->params[0]->type = new Identifier('string');
                $changed = true;
            }

            // Fix return type: add 'mixed' if missing.
            if ($method->returnType === null) {
                $method->returnType = new Identifier('mixed');
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }
}
