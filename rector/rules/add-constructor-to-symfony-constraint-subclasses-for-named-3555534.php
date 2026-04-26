<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3555534
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Since symfony/validator 7.4, setting constraint properties via the
// generic options array is deprecated. Each Constraint subclass must
// initialize its properties in its own constructor using named
// arguments. This rule adds the required constructor: string-typed
// properties with defaults are promoted; other typed properties get a
// nullable parameter plus an explicit $this->prop = $param ??
// $this->prop assignment.
//
// Before:
//   class MyConstraint extends \Symfony\Component\Validator\Constraint {
//     public int $limit = 100;
//     public string $message = 'Value exceeds limit.';
//   }
//
// After:
//   class MyConstraint extends \Symfony\Component\Validator\Constraint {
//     public int $limit = 100;
//   
//     public function __construct(
//       mixed $options = NULL,
//       ?int $limit = NULL,
//       public string $message = 'Value exceeds limit.',
//       ?array $groups = NULL,
//       mixed $payload = NULL,
//     ) {
//       parent::__construct($options, $groups, $payload);
//       $this->limit = $limit ?? $this->limit;
//     }
//   }


use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Property;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds explicit constructors to Symfony Constraint subclasses.
 *
 * Since symfony/validator 7.4, setting constraint properties via the generic
 * options array is deprecated. Each constraint must initialize its properties
 * in its own constructor using named arguments. This rule adds the required
 * constructor: string-typed properties with defaults are promoted; other typed
 * properties get a nullable parameter and an explicit assignment.
 */
final class AddSymfonyConstraintConstructorRector extends AbstractRector
{
    private const SYMFONY_CONSTRAINT_CLASS = 'Symfony\\Component\\Validator\\Constraint';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add constructor to Symfony Constraint subclasses to replace deprecated options-array initialization (symfony/validator 7.4+)',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyConstraint extends \Symfony\Component\Validator\Constraint {
    public int $limit = 100;
    public string $message = 'Value exceeds limit.';
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
class MyConstraint extends \Symfony\Component\Validator\Constraint {
    public int $limit = 100;

    public function __construct(
        mixed $options = NULL,
        ?int $limit = NULL,
        public string $message = 'Value exceeds limit.',
        ?array $groups = NULL,
        mixed $payload = NULL,
    ) {
        parent::__construct($options, $groups, $payload);
        $this->limit = $limit ?? $this->limit;
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

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Class_);

        if ($node->extends === null) {
            return null;
        }

        // Rector resolves use-statement imports to FullyQualified names.
        if (!$node->extends instanceof FullyQualified) {
            return null;
        }

        if ($node->extends->toString() !== self::SYMFONY_CONSTRAINT_CLASS) {
            return null;
        }

        // Skip if already has a constructor.
        if ($node->getMethod('__construct') !== null) {
            return null;
        }

        // Collect public non-static properties.
        $promotable = []; // string-typed with default → will be promoted
        $regular    = []; // everything else → keep as property, add nullable param

        foreach ($node->stmts as $stmt) {
            if (!$stmt instanceof Property) {
                continue;
            }
            if (!$stmt->isPublic() || ($stmt->flags & Modifiers::STATIC)) {
                continue;
            }

            foreach ($stmt->props as $propItem) {
                $propName    = $propItem->name->name;
                $propType    = $stmt->type;
                $propDefault = $propItem->default;

                $isStringType     = $propType instanceof Identifier && $propType->name === 'string';
                $hasStringDefault = $propDefault !== null;

                if ($isStringType && $hasStringDefault) {
                    $promotable[] = [
                        'name'    => $propName,
                        'type'    => $propType,
                        'default' => $propDefault,
                        'stmt'    => $stmt,
                    ];
                } else {
                    $regular[] = [
                        'name'    => $propName,
                        'type'    => $propType,
                        'default' => $propDefault,
                        'stmt'    => $stmt,
                    ];
                }
            }
        }

        if (empty($promotable) && empty($regular)) {
            return null;
        }

        [$params, $bodyStmts] = $this->buildConstructorContent($promotable, $regular);

        $constructor = new ClassMethod(
            '__construct',
            [
                'flags'  => Modifiers::PUBLIC,
                'params' => $params,
                'stmts'  => $bodyStmts,
            ]
        );

        // Remove promoted property declarations from the class body.
        $promotedNames  = array_column($promotable, 'name');
        $newStmts       = [];
        $insertAfterIdx = 0;

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Property) {
                $remaining = array_filter(
                    $stmt->props,
                    static fn (PropertyItem $pi) => !in_array($pi->name->name, $promotedNames, true)
                );

                if (empty($remaining)) {
                    $insertAfterIdx = count($newStmts);
                    continue;
                }

                $stmt->props = array_values($remaining);
                $newStmts[]  = $stmt;
                $insertAfterIdx = count($newStmts);
                continue;
            }

            $newStmts[] = $stmt;
        }

        array_splice($newStmts, $insertAfterIdx, 0, [$constructor]);
        $node->stmts = $newStmts;

        return $node;
    }

    /**
     * @param array<array{name: string, type: Node|null, default: Node\Expr|null, stmt: Property}> $promotable
     * @param array<array{name: string, type: Node|null, default: Node\Expr|null, stmt: Property}> $regular
     * @return array{list<Param>, list<Node\Stmt>}
     */
    private function buildConstructorContent(array $promotable, array $regular): array
    {
        $params    = [];
        $bodyStmts = [];

        // First param: mixed $options = NULL
        $optionsParam       = new Param(new Variable('options'), new ConstFetch(new Name('NULL')));
        $optionsParam->type = new Identifier('mixed');
        $params[]           = $optionsParam;

        // Regular (non-promoted) properties: nullable typed param + assignment.
        foreach ($regular as $data) {
            $paramType = $data['type'] !== null ? new NullableType($data['type']) : null;
            $param     = new Param(new Variable($data['name']), new ConstFetch(new Name('NULL')), $paramType);
            $params[]  = $param;

            $bodyStmts[] = new Expression(new Assign(
                new PropertyFetch(new Variable('this'), $data['name']),
                new Coalesce(
                    new Variable($data['name']),
                    new PropertyFetch(new Variable('this'), $data['name'])
                )
            ));
        }

        // Promoted string properties.
        foreach ($promotable as $data) {
            $param        = new Param(new Variable($data['name']), $data['default'], $data['type']);
            $param->flags = Modifiers::PUBLIC;
            $params[]     = $param;
        }

        // ?array $groups = NULL
        $params[] = new Param(
            new Variable('groups'),
            new ConstFetch(new Name('NULL')),
            new NullableType(new Identifier('array'))
        );

        // mixed $payload = NULL
        $payloadParam       = new Param(new Variable('payload'), new ConstFetch(new Name('NULL')));
        $payloadParam->type = new Identifier('mixed');
        $params[]           = $payloadParam;

        // parent::__construct($options, $groups, $payload)
        $bodyStmts[] = new Expression(new StaticCall(
            new Name('parent'),
            '__construct',
            [
                new Arg(new Variable('options')),
                new Arg(new Variable('groups')),
                new Arg(new Variable('payload')),
            ]
        ));

        return [$params, $bodyStmts];
    }
}
