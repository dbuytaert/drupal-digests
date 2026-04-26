<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3468204
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes the legacy $this->addToAssertionCount(1) call from setUp() in
// classes extending BrowserTestBase (or WebDriverTestBase). The call was
// a PHPUnit 6 workaround added because assertSession() did not increment
// PHPUnit's assertion counter; since UiHelperTrait::assertSession() now
// does increment the count, the workaround is redundant and misleads
// developers about actual test assertions.
//
// Before:
//   protected function setUp(): void {
//       parent::setUp();
//       // Ensure that the test is not marked as risky because of no assertions.
//       $this->addToAssertionCount(1);
//   }
//
// After:
//   protected function setUp(): void {
//       parent::setUp();
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes $this->addToAssertionCount(1) from setUp() in BrowserTestBase
 * subclasses. This was a PHPUnit 6 workaround because assertSession() did not
 * increment the PHPUnit assertion count. Since assertSession() now does
 * increment the count, the call is unnecessary and misleading.
 */
final class RemoveAddToAssertionCountFromSetUpRector extends AbstractRector
{
    /**
     * Known Drupal browser-test base-class short names.
     */
    private const BROWSER_TEST_BASES = [
        'BrowserTestBase',
        'WebDriverTestBase',
        'InstallerTestBase',
        'JavascriptTestBase',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove $this->addToAssertionCount(1) workaround from setUp() in BrowserTestBase subclasses',
            [
                new CodeSample(
                    <<<'CODE'
protected function setUp(): void {
    parent::setUp();
    // Ensure that the test is not marked as risky because of no assertions.
    $this->addToAssertionCount(1);
}
CODE,
                    <<<'CODE'
protected function setUp(): void {
    parent::setUp();
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
        if (!$this->extendsBrowserTestBase($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            if (!$this->isName($method, 'setUp')) {
                continue;
            }
            if ($this->removeAddToAssertionCount1($method)) {
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    /**
     * Returns true when the class directly extends one of the known Drupal
     * browser-test base classes (short-name match).
     */
    private function extendsBrowserTestBase(Class_ $class): bool
    {
        if ($class->extends === null) {
            return false;
        }
        $parentShortName = $class->extends->getLast();
        return in_array($parentShortName, self::BROWSER_TEST_BASES, true);
    }

    /**
     * Removes every `$this->addToAssertionCount(1)` statement from the given
     * method body. Returns true when at least one statement was removed.
     */
    private function removeAddToAssertionCount1(ClassMethod $method): bool
    {
        if ($method->stmts === null || $method->stmts === []) {
            return false;
        }

        $changed = false;
        $newStmts = [];
        foreach ($method->stmts as $stmt) {
            if ($this->isAddToAssertionCount1Statement($stmt)) {
                $changed = true;
                continue;
            }
            $newStmts[] = $stmt;
        }

        if ($changed) {
            $method->stmts = $newStmts;
        }

        return $changed;
    }

    /**
     * Returns true when $stmt is exactly `$this->addToAssertionCount(1);`.
     */
    private function isAddToAssertionCount1Statement(Node $stmt): bool
    {
        if (!$stmt instanceof Expression) {
            return false;
        }
        $expr = $stmt->expr;
        if (!$expr instanceof MethodCall) {
            return false;
        }
        if (!$expr->var instanceof Variable || !$this->isName($expr->var, 'this')) {
            return false;
        }
        if (!$this->isName($expr->name, 'addToAssertionCount')) {
            return false;
        }
        if (count($expr->args) !== 1) {
            return false;
        }
        $firstArg = $expr->args[0];
        if (!$firstArg instanceof Arg) {
            return false;
        }
        return $firstArg->value instanceof LNumber && $firstArg->value->value === 1;
    }
}
