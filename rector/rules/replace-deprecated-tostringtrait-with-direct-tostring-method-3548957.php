<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3548957
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes use Drupal\Component\Utility\ToStringTrait from classes and
// inserts a direct public function __toString(): string that returns
// (string) $this->render(). The trait was a PHP 7.x workaround for fatal
// errors inside __toString() and is deprecated in drupal:11.4.0 with
// removal in drupal:13.0.0. Exception handling is no longer needed on
// PHP 8+.
//
// Before:
//   use Drupal\Component\Utility\ToStringTrait;
//   
//   class MyFormattableMarkup {
//     use ToStringTrait;
//   
//     public function render() {
//       return 'hello world';
//     }
//   }
//
// After:
//   class MyFormattableMarkup {
//     public function __toString(): string
//     {
//         return (string) $this->render();
//     }
//   
//     public function render() {
//       return 'hello world';
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\Cast\String_ as CastString_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\TraitUse;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces use of deprecated Drupal\Component\Utility\ToStringTrait with a
 * direct __toString() method that calls $this->render().
 */
final class RemoveDrupalToStringTraitRector extends AbstractRector
{
    private const TRAIT_FQCN = 'Drupal\Component\Utility\ToStringTrait';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace use of deprecated Drupal ToStringTrait with a direct __toString() method',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Drupal\Component\Utility\ToStringTrait;

class MyClass {
    use ToStringTrait;

    public function render() {
        return 'hello';
    }
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyClass {
    public function __toString(): string {
        return (string) $this->render();
    }

    public function render() {
        return 'hello';
    }
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasToStringTrait = false;

        foreach ($node->stmts as $key => $stmt) {
            if (!$stmt instanceof TraitUse) {
                continue;
            }

            foreach ($stmt->traits as $traitKey => $traitName) {
                if ($this->isName($traitName, self::TRAIT_FQCN)) {
                    $hasToStringTrait = true;
                    unset($stmt->traits[$traitKey]);
                }
            }

            // Remove the TraitUse stmt if now empty.
            if ($stmt->traits === []) {
                unset($node->stmts[$key]);
            }
        }

        if (!$hasToStringTrait) {
            return null;
        }

        // Re-index statements.
        $node->stmts = array_values($node->stmts);

        // Add __toString() if the class does not already define one.
        if ($node->getMethod('__toString') === null) {
            $toStringMethod = $this->buildToStringMethod();
            array_unshift($node->stmts, $toStringMethod);
        }

        return $node;
    }

    private function buildToStringMethod(): ClassMethod
    {
        $renderCall = new MethodCall(
            new Variable('this'),
            new Identifier('render')
        );
        $cast = new CastString_($renderCall);
        $return = new Return_($cast);

        $method = new ClassMethod(new Identifier('__toString'));
        $method->flags = Class_::MODIFIER_PUBLIC;
        $method->returnType = new Identifier('string');
        $method->stmts = [$return];

        return $method;
    }
}
