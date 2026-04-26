<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3581058
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Strips ->expects($this->any()) from PHPUnit mock method chains.
// PHPUnit 12.5 deprecated $this->any() because it conveys no real
// expectation; a chain like
// $mock->expects($this->any())->method('foo')->willReturn('bar') is
// mechanically reduced to $mock->method('foo')->willReturn('bar'). Tests
// that create mocks with no expectations at all should also be converted
// to createStub() manually.
//
// Before:
//   $mock->expects($this->any())->method('getFoo')->willReturn('bar');
//
// After:
//   $mock->method('getFoo')->willReturn('bar');


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes ->expects($this->any()) from PHPUnit mock method chains.
 *
 * In PHPUnit 12.5+ $this->any() is deprecated. A chain like
 *   $mock->expects($this->any())->method('foo')->willReturn('bar')
 * becomes:
 *   $mock->method('foo')->willReturn('bar')
 *
 * Complements converting expectation-less createMock() calls to createStub().
 */
final class RemoveExpectsAnyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated ->expects($this->any()) from PHPUnit mock method chains',
            [
                new CodeSample(
                    '$mock->expects($this->any())->method(\'foo\')->willReturn(\'bar\');',
                    '$mock->method(\'foo\')->willReturn(\'bar\');'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        // We are looking for a MethodCall node whose ->var is the
        // expects($this->any()) call we want to strip. Walking up the chain
        // from any node, check if the immediate callee is expects(any()).
        if (!$this->isExpectsAnyCall($node->var)) {
            return null;
        }

        // Replace the var (the expects($this->any()) call) with its own var
        // (the original mock object), effectively removing expects(any()) from
        // the chain.
        $node->var = $node->var->var;

        return $node;
    }

    /**
     * Returns true when $expr is a call of the form $something->expects($this->any()).
     */
    private function isExpectsAnyCall(Node $expr): bool
    {
        if (!$expr instanceof MethodCall) {
            return false;
        }

        if (!$this->isName($expr->name, 'expects')) {
            return false;
        }

        if (count($expr->args) !== 1) {
            return false;
        }

        $arg = $expr->args[0];
        if (!$arg instanceof Node\Arg) {
            return false;
        }

        $argValue = $arg->value;
        if (!$argValue instanceof MethodCall) {
            return false;
        }

        return $this->isName($argValue->name, 'any')
            && count($argValue->args) === 0;
    }
}
