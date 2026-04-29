<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3217904
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In PHPUnit 10 the TestCase::getName() method was renamed to name().
// This rule replaces $this->getName() and $this->getName(false) calls
// inside PHPUnit TestCase subclasses with $this->name(), enabling
// contrib and custom modules to be compatible with both Drupal 10
// (PHPUnit 9) and Drupal 11 (PHPUnit 10).
//
// Before:
//   $this->getName()
//
// After:
//   $this->name()


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated PHPUnit 9 TestCase::getName() with name() for PHPUnit 10.
 */
final class GetNameToNameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated TestCase::getName() with TestCase::name() for PHPUnit 10 compatibility',
            [
                new CodeSample(
                    '$this->getName()',
                    '$this->name()'
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
        // Must be a call on $this.
        if (!$node->var instanceof \PhpParser\Node\Expr\Variable) {
            return null;
        }
        if ($this->getName($node->var) !== 'this') {
            return null;
        }

        // Must be getName().
        if (!$this->isName($node->name, 'getName')) {
            return null;
        }

        // Must be inside a PHPUnit TestCase subclass.
        if (!$this->isObjectType($node->var, new ObjectType('PHPUnit\\Framework\\TestCase'))) {
            return null;
        }

        // Accepts 0 args or a single `false` arg (getName(false) = name without data set).
        $args = $node->args;
        if (count($args) === 0) {
            // OK, replace with name().
        } elseif (count($args) === 1) {
            $arg = $args[0];
            if (!$arg instanceof \PhpParser\Node\Arg) {
                return null;
            }
            // Only handle getName(false) / getName(FALSE).
            if (!$arg->value instanceof \PhpParser\Node\Expr\ConstFetch) {
                return null;
            }
            $constName = $this->getName($arg->value->name);
            if (strtolower($constName) !== 'false') {
                return null;
            }
        } else {
            return null;
        }

        // Replace with $this->name().
        $node->name = new \PhpParser\Node\Identifier('name');
        $node->args = [];
        return $node;
    }
}
