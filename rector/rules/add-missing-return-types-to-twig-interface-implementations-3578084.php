<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3578084
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds native PHP return type declarations to methods that implement
// Twig interfaces (ExtensionInterface, NodeVisitorInterface,
// TokenParserInterface, LoaderInterface) or extend Twig\Node\Node. Twig
// 3 emits deprecation notices for missing return types on these
// contracts, and Twig 4 will enforce them natively. Drupal core added
// the types in issue #3578084; this rule applies the same fix to contrib
// and custom modules.
//
// Before:
//   use Twig\Extension\AbstractExtension;
//   use Twig\Node\Node;
//   use Twig\Compiler;
//   use Twig\NodeVisitor\NodeVisitorInterface;
//   use Twig\Environment;
//   
//   class MyTwigExtension extends AbstractExtension {
//       public function getFunctions() { return []; }
//       public function getFilters() { return []; }
//   }
//   
//   class MyTwigNode extends Node {
//       public function compile(Compiler $compiler) { }
//   }
//   
//   class MyNodeVisitor implements NodeVisitorInterface {
//       public function enterNode(Node $node, Environment $env) { return $node; }
//       public function leaveNode(Node $node, Environment $env) { return $node; }
//       public function getPriority() { return 0; }
//   }
//
// After:
//   use Twig\Extension\AbstractExtension;
//   use Twig\Node\Node;
//   use Twig\Compiler;
//   use Twig\NodeVisitor\NodeVisitorInterface;
//   use Twig\Environment;
//   
//   class MyTwigExtension extends AbstractExtension {
//       public function getFunctions(): array { return []; }
//       public function getFilters(): array { return []; }
//   }
//   
//   class MyTwigNode extends Node {
//       public function compile(Compiler $compiler): void { }
//   }
//   
//   class MyNodeVisitor implements NodeVisitorInterface {
//       public function enterNode(Node $node, Environment $env): \Twig\Node\Node { return $node; }
//       public function leaveNode(Node $node, Environment $env): ?\Twig\Node\Node { return $node; }
//       public function getPriority(): int { return 0; }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds missing return types to methods that implement Twig interfaces.
 *
 * Twig 3 began emitting deprecation notices for implementations of
 * ExtensionInterface, NodeVisitorInterface, TokenParserInterface,
 * LoaderInterface, and Node::compile() that lack native PHP return type
 * declarations. Drupal core added the types in issue #3578084. This rule
 * does the same for contrib and custom modules that extend or implement the
 * same Twig contracts.
 */
final class AddTwigImplementationReturnTypesRector extends AbstractRector
{
    private const TWIG_NODE_CLASS = 'Twig\\Node\\Node';

    /**
     * Map: (class/interface tail) => [method => return-type-spec]
     *
     * Return-type-spec:
     *   'void' | 'bool' | 'int' | 'string' | 'array'  – scalar identifier
     *   'node'          – \Twig\Node\Node
     *   'nullable_node' – ?\Twig\Node\Node
     */
    private const CLASS_METHOD_RETURN_TYPES = [
        'Extension\\ExtensionInterface' => [
            'getFunctions'    => 'array',
            'getFilters'      => 'array',
            'getNodeVisitors' => 'array',
            'getTokenParsers' => 'array',
            'getTests'        => 'array',
            'getOperators'    => 'array',
        ],
        'Extension\\AbstractExtension' => [
            'getFunctions'    => 'array',
            'getFilters'      => 'array',
            'getNodeVisitors' => 'array',
            'getTokenParsers' => 'array',
            'getTests'        => 'array',
            'getOperators'    => 'array',
        ],
        'NodeVisitor\\NodeVisitorInterface' => [
            'enterNode'   => 'node',
            'leaveNode'   => 'nullable_node',
            'getPriority' => 'int',
        ],
        'TokenParser\\TokenParserInterface' => [
            'parse'  => 'node',
            'getTag' => 'string',
        ],
        'TokenParser\\AbstractTokenParser' => [
            'parse'  => 'node',
            'getTag' => 'string',
        ],
        'Loader\\LoaderInterface' => [
            'exists'      => 'bool',
            'isFresh'     => 'bool',
            'getCacheKey' => 'string',
        ],
        'Node\\Node' => [
            'compile' => 'void',
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing return types to methods implementing Twig interfaces to prevent Twig deprecation notices',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Twig\Node\Node;
use Twig\Compiler;

class MyTwigNode extends Node {
    public function compile(Compiler $compiler) {
        // ...
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Twig\Node\Node;
use Twig\Compiler;

class MyTwigNode extends Node {
    public function compile(Compiler $compiler): void {
        // ...
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        $methodReturnTypes = $this->collectApplicableReturnTypes($node);
        if ($methodReturnTypes === []) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            if ($method->returnType !== null) {
                continue;
            }
            $methodName = $this->getName($method);
            if ($methodName === null || !isset($methodReturnTypes[$methodName])) {
                continue;
            }
            $method->returnType = $this->buildReturnType($methodReturnTypes[$methodName]);
            $changed = true;
        }

        return $changed ? $node : null;
    }

    /**
     * Returns the merged method->returnTypeSpec map for all matching Twig
     * interfaces/parent classes the given class implements or extends.
     *
     * @return array<string, string>
     */
    private function collectApplicableReturnTypes(Class_ $class): array
    {
        $result = [];
        foreach (self::CLASS_METHOD_RETURN_TYPES as $nameTail => $methodTypes) {
            if ($this->classMatchesTwigType($class, $nameTail)) {
                $result = array_merge($result, $methodTypes);
            }
        }
        return $result;
    }

    /**
     * Returns true when the class extends or implements a type whose FQCN
     * ends with $nameTail (e.g. "NodeVisitor\\NodeVisitorInterface") or whose
     * short name equals the last segment of $nameTail.
     */
    private function classMatchesTwigType(Class_ $class, string $nameTail): bool
    {
        $shortName = substr($nameTail, (int) strrpos($nameTail, '\\') + 1);
        $fqcn      = 'Twig\\' . $nameTail;

        if ($class->extends !== null) {
            $extendsFqcn = $class->extends->toString();
            if (
                $extendsFqcn === $shortName
                || $extendsFqcn === $fqcn
                || str_ends_with($extendsFqcn, '\\' . $shortName)
            ) {
                return true;
            }
        }

        foreach ($class->implements as $implement) {
            $name = $implement->toString();
            if (
                $name === $shortName
                || $name === $fqcn
                || str_ends_with($name, '\\' . $shortName)
            ) {
                return true;
            }
        }

        return false;
    }

    private function buildReturnType(string $spec): Identifier|FullyQualified|NullableType
    {
        return match ($spec) {
            'node'          => new FullyQualified(self::TWIG_NODE_CLASS),
            'nullable_node' => new NullableType(new FullyQualified(self::TWIG_NODE_CLASS)),
            default         => new Identifier($spec),
        };
    }
}
