<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3557372
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Detects calls to getPropertyDefinition() where the argument is
// getMainPropertyName() inlined as the argument. Since
// getMainPropertyName() can return null and
// FieldStorageDefinitionInterface::getPropertyDefinition(string $name)
// now declares a non-nullable string parameter (Drupal 11.3+), passing
// null triggers a PHP 8.5 deprecation and will throw an exception in
// Drupal 12. The rule extracts the call into a variable and wraps the
// getPropertyDefinition() call in a !== null guard.
//
// Before:
//   $property_definition = $item_definition->getPropertyDefinition($item_definition->getMainPropertyName());
//
// After:
//   $mainPropertyName = $item_definition->getMainPropertyName();
//   if ($mainPropertyName !== null) {
//       $property_definition = $item_definition->getPropertyDefinition($mainPropertyName);
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Guards getPropertyDefinition() calls where the argument is getMainPropertyName(),
 * which can return null. FieldStorageDefinitionInterface::getPropertyDefinition()
 * now declares string $name (Drupal 11.3+), so passing null triggers a PHP 8.5
 * deprecation and will throw an exception in Drupal 12.
 */
final class GuardGetPropertyDefinitionNullArgRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Guard getPropertyDefinition() calls where the argument is getMainPropertyName(), which can return null, to avoid PHP 8.5 deprecation and Drupal 12 exception.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$property_definition = $item_definition->getPropertyDefinition($item_definition->getMainPropertyName());
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$mainPropertyName = $item_definition->getMainPropertyName();
if ($mainPropertyName !== null) {
    $property_definition = $item_definition->getPropertyDefinition($mainPropertyName);
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return null|Expression|Node\Stmt[]
     */
    public function refactor(Node $node): null|Node|array
    {
        $inner = $node->expr;

        // Case A: assignment: $x = $obj->getPropertyDefinition($arg)
        if ($inner instanceof Assign && $inner->expr instanceof MethodCall) {
            $methodCall = $inner->expr;
        }
        // Case B: standalone call: $obj->getPropertyDefinition($arg);
        elseif ($inner instanceof MethodCall) {
            $methodCall = $inner;
        } else {
            return null;
        }

        // Must be a call to getPropertyDefinition().
        if (!$this->isName($methodCall->name, 'getPropertyDefinition')) {
            return null;
        }

        // Must have exactly one argument.
        if (count($methodCall->getArgs()) !== 1) {
            return null;
        }

        $arg = $methodCall->getArgs()[0]->value;

        // The argument must itself be a call to getMainPropertyName().
        if (!$arg instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($arg->name, 'getMainPropertyName')) {
            return null;
        }

        $varName = 'mainPropertyName';
        $tempVar = new Variable($varName);

        // $mainPropertyName = $obj->getMainPropertyName();
        $extractedAssign = new Expression(
            new Assign($tempVar, $arg)
        );

        // Replace the inlined getMainPropertyName() call with the variable.
        $methodCall->args = [new Arg(new Variable($varName))];

        // if ($mainPropertyName !== null) { <original statement> }
        $notIdenticalNull = new NotIdentical(
            new Variable($varName),
            $this->nodeFactory->createNull()
        );

        $ifStmt = new If_($notIdenticalNull, [
            'stmts' => [$node],
        ]);

        return [$extractedAssign, $ifStmt];
    }
}
