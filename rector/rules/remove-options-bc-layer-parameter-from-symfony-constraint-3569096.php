<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes the mixed $options = NULL BC-layer first parameter from
 * constructors of Symfony\Component\Validator\Constraint subclasses, and
 * updates the parent::__construct($options, $groups, $payload) call to
 * use Symfony 8-compatible named arguments parent::__construct(groups:
 * $groups, payload: $payload). Symfony 8 drops the legacy array-options
 * constructor, so Drupal 12 removes this pattern from all core
 * Constraint classes; contrib classes with the same pattern must follow.
 *
 * Before:
 *   use Symfony\Component\Validator\Constraint;
 *   
 *   class MyConstraint extends Constraint {
 *       public function __construct(
 *           mixed $options = NULL,
 *           public string $message = 'Value is invalid.',
 *           ?array $groups = NULL,
 *           mixed $payload = NULL,
 *       ) {
 *           parent::__construct($options, $groups, $payload);
 *       }
 *   }
 *
 * After:
 *   use Symfony\Component\Validator\Constraint;
 *   
 *   class MyConstraint extends Constraint {
 *       public function __construct(
 *           public string $message = 'Value is invalid.',
 *           ?array $groups = NULL,
 *           mixed $payload = NULL,
 *       ) {
 *           parent::__construct(groups: $groups, payload: $payload);
 *       }
 *   }
 *
 * Caveats:
 *   Only transforms the common 3-argument parent::__construct($options,
 *   $groups, $payload) pattern; cases where the parent constructor
 *   takes additional positional arguments (e.g. subclasses of Symfony's
 *   concrete Email or Range constraints) are left unchanged. Requires
 *   symfony/validator to be in PHPStan's autoload path so the
 *   Constraint hierarchy can be resolved.
 *
 * @see https://www.drupal.org/node/3569096
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveOptionsArgFromConstraintConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the legacy $options BC-layer parameter from Symfony Constraint subclass constructors and update the parent::__construct() call to use named arguments.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\Validator\Constraint;

class MyConstraint extends Constraint {
    public function __construct(
        mixed $options = NULL,
        public string $message = 'Value is invalid.',
        ?array $groups = NULL,
        mixed $payload = NULL,
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
use Symfony\Component\Validator\Constraint;

class MyConstraint extends Constraint {
    public function __construct(
        public string $message = 'Value is invalid.',
        ?array $groups = NULL,
        mixed $payload = NULL,
    ) {
        parent::__construct(groups: $groups, payload: $payload);
    }
}
CODE_SAMPLE,
            )],
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
        if (!$node instanceof Class_) {
            return null;
        }
        if ($node->extends === null) {
            return null;
        }
        if (!$this->isObjectType($node->extends, new ObjectType('Symfony\Component\Validator\Constraint'))) {
            return null;
        }

        $constructMethod = $node->getMethod('__construct');
        if ($constructMethod === null) {
            return null;
        }
        if (count($constructMethod->params) === 0) {
            return null;
        }

        // First parameter must be named $options with a NULL default
        $firstParam = $constructMethod->params[0];
        if (!$this->isName($firstParam->var, 'options')) {
            return null;
        }
        if ($firstParam->default === null) {
            return null;
        }
        if (!$firstParam->default instanceof ConstFetch) {
            return null;
        }
        if (!$this->isName($firstParam->default->name, 'null')) {
            return null;
        }
        // The parameter must not already be a promoted property (it's the BC options, not an option property)
        if ($firstParam->flags !== 0) {
            return null;
        }

        // Bail when the constructor body uses $options beyond the
        // single reference in parent::__construct(). The rule rewrites
        // the signature and parent call, but won't touch the body —
        // and a body that reads $options['key'] would silently break
        // with an undefined-variable use after we strip the param.
        // The expected reference count is exactly 1 (the parent call).
        $optionsRefs = 0;
        $this->traverseNodesWithCallable($constructMethod->stmts ?? [], function (Node $n) use (&$optionsRefs) {
            if ($n instanceof Variable && $this->getName($n) === 'options') {
                $optionsRefs++;
            }
            return null;
        });
        if ($optionsRefs > 1) {
            return null;
        }

        // Remove the $options parameter from the constructor
        array_shift($constructMethod->params);

        // Update the parent::__construct() call inside the method body
        $this->traverseNodesWithCallable($constructMethod->stmts ?? [], function (Node $innerNode): ?Node {
            if (!$innerNode instanceof StaticCall) {
                return null;
            }
            if (!$innerNode->class instanceof Name) {
                return null;
            }
            if ($innerNode->class->toString() !== 'parent') {
                return null;
            }
            if (!$this->isName($innerNode->name, '__construct')) {
                return null;
            }
            if (count($innerNode->args) < 1) {
                return null;
            }
            $firstArg = $innerNode->args[0];
            if (!$firstArg instanceof Arg) {
                return null;
            }
            // Skip already-named first argument
            if ($firstArg->name !== null) {
                return null;
            }
            if (!$firstArg->value instanceof Variable) {
                return null;
            }
            if (!$this->isName($firstArg->value, 'options')) {
                return null;
            }
            // Remove the $options arg; convert the remaining two positional args to named args
            $remaining = array_slice($innerNode->args, 1);
            if (count($remaining) === 2
                && $remaining[0] instanceof Arg
                && $remaining[0]->name === null
                && $remaining[1] instanceof Arg
                && $remaining[1]->name === null
            ) {
                $remaining[0]->name = new Identifier('groups');
                $remaining[1]->name = new Identifier('payload');
            }
            $innerNode->args = $remaining;
            return $innerNode;
        });

        return $node;
    }
}
