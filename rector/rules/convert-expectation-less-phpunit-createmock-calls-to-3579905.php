<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3579905
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites $this->createMock(X::class) to $this->createStub(X::class)
// when the returned object is never used with an ->expects(...) call.
// PHPUnit 12.5+ emits a notice for mock objects that configure no
// expectations, because the semantically correct API is createStub().
// The rule inspects each enclosing function or method body and only
// converts variables where no expects() calls are found, preserving real
// mocks that enforce call counts.
//
// Before:
//   $mock = $this->createMock(SomeInterface::class);
//   $mock->method('foo')->willReturn('bar');
//
// After:
//   $mock = $this->createStub(SomeInterface::class);
//   $mock->method('foo')->willReturn('bar');


use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\NodeFinder;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts $this->createMock() to $this->createStub() when no expectations
 * are configured on the resulting mock object.
 *
 * PHPUnit 12.5+ emits a notice when a mock object created via createMock()
 * has no ->expects() calls. The correct replacement is createStub(), which
 * does not enforce expectations.
 */
final class CreateMockToCreateStubRector extends AbstractRector
{
    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $this->nodeFinder = new NodeFinder();
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace createMock() with createStub() for mock objects that never configure expectations',
            [
                new CodeSample(
                    '$mock = $this->createMock(SomeInterface::class);' . "\n" .
                    '$mock->method(\'foo\')->willReturn(\'bar\');',
                    '$mock = $this->createStub(SomeInterface::class);' . "\n" .
                    '$mock->method(\'foo\')->willReturn(\'bar\');'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class, Closure::class, ArrowFunction::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Collect all createMock() assignments in this scope.
        /** @var Assign[] $assignments */
        $assignments = $this->nodeFinder->find(
            $node,
            function (Node $n): bool {
                if (!$n instanceof Assign) {
                    return false;
                }
                if (!$n->expr instanceof MethodCall) {
                    return false;
                }
                if (!$n->var instanceof Variable) {
                    return false;
                }
                $methodName = $n->expr->name instanceof Identifier
                    ? $n->expr->name->toString()
                    : null;
                return $methodName === 'createMock';
            }
        );

        if ($assignments === []) {
            return null;
        }

        // Collect all variable names that have ->expects(...) called on them.
        $varsWithExpects = [];
        $this->nodeFinder->find(
            $node,
            function (Node $n) use (&$varsWithExpects): bool {
                if (!$n instanceof MethodCall) {
                    return false;
                }
                $methodName = $n->name instanceof Identifier
                    ? $n->name->toString()
                    : null;
                if ($methodName !== 'expects') {
                    return false;
                }
                // The receiver must be a plain variable.
                if (!$n->var instanceof Variable) {
                    return false;
                }
                $varName = $n->var->name;
                if (is_string($varName)) {
                    $varsWithExpects[$varName] = true;
                }
                return false;
            }
        );

        $changed = false;
        foreach ($assignments as $assign) {
            /** @var Variable $var */
            $var = $assign->var;
            $varName = is_string($var->name) ? $var->name : null;
            if ($varName === null) {
                continue;
            }
            if (isset($varsWithExpects[$varName])) {
                // This mock has expectations; leave it as createMock.
                continue;
            }
            // No expects() calls for this variable; rename to createStub.
            /** @var MethodCall $call */
            $call = $assign->expr;
            $call->name = new Identifier('createStub');
            $changed = true;
        }

        return $changed ? $node : null;
    }
}
