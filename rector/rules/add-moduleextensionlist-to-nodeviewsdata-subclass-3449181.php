<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In drupal:11.2.0, NodeViewsData::__construct() gained a required
 * $moduleExtensionList parameter (type ?ModuleExtensionList), which will
 * be mandatory in drupal:12.0.0. Any subclass that overrides
 * __construct() and calls parent::__construct() with only 6 arguments
 * triggers the deprecation. This rule adds the missing parameter to the
 * subclass signature and forwards it to the parent::__construct() call.
 *
 * Before:
 *   class MyNodeViewsData extends \Drupal\node\NodeViewsData {
 *     public function __construct($a, $b, $c, $d, $e, $f) {
 *       parent::__construct($a, $b, $c, $d, $e, $f);
 *     }
 *   }
 *
 * After:
 *   class MyNodeViewsData extends \Drupal\node\NodeViewsData {
 *     public function __construct($a, $b, $c, $d, $e, $f, ?\Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList = NULL) {
 *       parent::__construct($a, $b, $c, $d, $e, $f, $moduleExtensionList);
 *     }
 *   }
 *
 * Caveats:
 *   The rule only fixes subclasses whose __construct() has exactly 6
 *   parameters (the original signature). Subclasses that already
 *   declared a 7th parameter are left unchanged. When a subclass
 *   overrides createInstance() and calls new static(...) without the
 *   service, a manual update of that method is also required to inject
 *   $container->get('extension.list.module') at position 6.
 *
 * @see https://www.drupal.org/node/3449181
 * @deprecated drupal:11.2.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the $moduleExtensionList parameter to constructors of NodeViewsData
 * subclasses that still call parent::__construct() with only 6 arguments.
 *
 * Deprecated in drupal:11.2.0, required in drupal:12.0.0.
 * @see https://www.drupal.org/node/3493129
 */
final class NodeViewsDataModuleExtensionListRector extends AbstractRector
{
    private const PARENT_CLASS_SHORT = 'NodeViewsData';
    private const PARENT_CLASS_FQCN  = 'Drupal\\node\\NodeViewsData';
    private const PARAM_NAME         = 'moduleExtensionList';
    private const PARAM_TYPE         = 'Drupal\\Core\\Extension\\ModuleExtensionList';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add $moduleExtensionList parameter to NodeViewsData subclass constructors (deprecated in drupal:11.2.0).',
            [new CodeSample(
                <<<'CODE'
class MyNodeViewsData extends \Drupal\node\NodeViewsData {
  public function __construct($a, $b, $c, $d, $e, $f) {
    parent::__construct($a, $b, $c, $d, $e, $f);
  }
}
CODE,
                <<<'CODE'
class MyNodeViewsData extends \Drupal\node\NodeViewsData {
  public function __construct($a, $b, $c, $d, $e, $f, ?\Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList = NULL) {
    parent::__construct($a, $b, $c, $d, $e, $f, $moduleExtensionList);
  }
}
CODE,
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
        // Must extend NodeViewsData (but not BE NodeViewsData itself).
        if (!$this->extendsNodeViewsData($node)) {
            return null;
        }
        if ($node->name !== null && $this->isName($node->name, self::PARENT_CLASS_SHORT)) {
            return null;
        }

        $changed = false;

        foreach ($node->getMethods() as $classMethod) {
            if (!$this->isName($classMethod, '__construct')) {
                continue;
            }

            // Skip if the 7th parameter already exists.
            if (isset($classMethod->params[6])) {
                continue;
            }

            // Add the nullable parameter with default NULL.
            $param = new Param(
                new Variable(self::PARAM_NAME),
                $this->nodeFactory->createNull(),
                new NullableType(new Name\FullyQualified(self::PARAM_TYPE)),
            );
            $classMethod->params[6] = $param;

            // Forward the variable in any parent::__construct() calls inside.
            $this->traverseNodesWithCallable(
                $classMethod->stmts ?? [],
                function (Node $innerNode): ?Node {
                    if (!$innerNode instanceof StaticCall) {
                        return null;
                    }
                    if (!$this->isName($innerNode->class, 'parent')) {
                        return null;
                    }
                    if (!$this->isName($innerNode->name, '__construct')) {
                        return null;
                    }
                    // Only extend calls with exactly 6 args.
                    if (count($innerNode->args) !== 6) {
                        return null;
                    }
                    $innerNode->args[] = new Arg(new Variable(self::PARAM_NAME));
                    return $innerNode;
                }
            );

            $changed = true;
        }

        return $changed ? $node : null;
    }

    /**
     * Check if the class extends NodeViewsData, using both type resolution (when
     * types are available) and a raw AST name check (when the autoloader is absent).
     */
    private function extendsNodeViewsData(Class_ $class): bool
    {
        // Type-resolution based check (works when Drupal is autoloadable).
        if ($this->isObjectType($class, new ObjectType(self::PARENT_CLASS_FQCN))) {
            return true;
        }

        // Fallback: raw AST extends clause check.
        if (!$class->extends instanceof Name) {
            return false;
        }
        $extendedName = $class->extends->toString();
        return $extendedName === self::PARENT_CLASS_SHORT
            || $extendedName === self::PARENT_CLASS_FQCN;
    }
}
