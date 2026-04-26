<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3582118
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes use Drupal\Tests\PhpUnitCompatibilityTrait; namespace imports
// and use PhpUnitCompatibilityTrait; in-class trait uses. The trait was
// a no-op since PHPUnit 11 and was deleted from Drupal core in issue
// #3582118. Any test class that still references it will get a fatal
// class-not-found error at runtime.
//
// Before:
//   use Drupal\KernelTests\KernelTestBase;
//   use Drupal\Tests\PhpUnitCompatibilityTrait;
//   
//   class MyModuleKernelTest extends KernelTestBase {
//     use PhpUnitCompatibilityTrait;
//   
//     public function testSomething(): void {
//       $this->assertTrue(TRUE);
//     }
//   }
//
// After:
//   use Drupal\KernelTests\KernelTestBase;
//   
//   class MyModuleKernelTest extends KernelTestBase {
//   
//     public function testSomething(): void {
//       $this->assertTrue(TRUE);
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitor;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes use statements for the deleted PhpUnitCompatibilityTrait.
 *
 * Drupal\Tests\PhpUnitCompatibilityTrait was removed in Drupal main (issue
 * #3582118). The trait was a no-op since PHPUnit 11 and is no longer present
 * in Drupal core. Any test class that imports and uses the trait will get a
 * class-not-found fatal error; this rule removes both the namespace import
 * statement and the in-class trait use statement.
 */
final class RemovePhpUnitCompatibilityTraitRector extends AbstractRector
{
    private const TRAIT_FQCN = 'Drupal\\Tests\\PhpUnitCompatibilityTrait';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the deleted Drupal\\Tests\\PhpUnitCompatibilityTrait import and in-class use statement',
            [
                new CodeSample(
                    'use Drupal\\Tests\\PhpUnitCompatibilityTrait;' . "\n" .
                    'class MyTest extends KernelTestBase { use PhpUnitCompatibilityTrait; }',
                    'class MyTest extends KernelTestBase {}'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Use_::class, Class_::class];
    }

    /**
     * @return NodeVisitor::REMOVE_NODE|Node|null
     */
    public function refactor(Node $node): int|Node|null
    {
        if ($node instanceof Use_) {
            return $this->refactorUseStatement($node);
        }

        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }

        return null;
    }

    /**
     * @return NodeVisitor::REMOVE_NODE|Use_|null
     */
    private function refactorUseStatement(Use_ $node): int|Use_|null
    {
        if ($node->type !== Use_::TYPE_NORMAL) {
            return null;
        }

        $changed = false;
        foreach ($node->uses as $key => $useUse) {
            if ($useUse->name->toString() === self::TRAIT_FQCN) {
                unset($node->uses[$key]);
                $changed = true;
            }
        }

        if (!$changed) {
            return null;
        }

        if ($node->uses === []) {
            return NodeVisitor::REMOVE_NODE;
        }

        return $node;
    }

    private function refactorClass(Class_ $node): ?Class_
    {
        $changed = false;
        foreach ($node->stmts as $key => $stmt) {
            if (!$stmt instanceof TraitUse) {
                continue;
            }
            foreach ($stmt->traits as $traitKey => $traitName) {
                if ($this->isName($traitName, self::TRAIT_FQCN)
                    || $this->isName($traitName, 'PhpUnitCompatibilityTrait')
                ) {
                    unset($stmt->traits[$traitKey]);
                    $changed = true;
                }
            }
            if ($stmt->traits === [] && $stmt->adaptations === []) {
                unset($node->stmts[$key]);
            }
        }

        return $changed ? $node : null;
    }
}
