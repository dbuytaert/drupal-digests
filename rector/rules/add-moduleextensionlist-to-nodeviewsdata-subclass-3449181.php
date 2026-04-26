<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3449181
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.2.0, NodeViewsData::__construct() gained a seventh
// parameter ?ModuleExtensionList $moduleExtensionList (required in
// 12.0.0, see change record #3493129). This rule finds subclasses that
// still call parent::__construct() with only 6 arguments, adds the
// $moduleExtensionList nullable parameter to the constructor signature,
// passes it to parent::__construct(), and injects extension.list.module
// via $container->get() inside createInstance().
//
// Before:
//   class MyNodeViewsData extends NodeViewsData {
//       public function __construct(
//           EntityTypeInterface $entity_type,
//           SqlEntityStorageInterface $storage_controller,
//           EntityTypeManagerInterface $entity_type_manager,
//           ModuleHandlerInterface $module_handler,
//           TranslationInterface $translation_manager,
//           EntityFieldManagerInterface $entity_field_manager,
//       ) {
//           parent::__construct($entity_type, $storage_controller, $entity_type_manager, $module_handler, $translation_manager, $entity_field_manager);
//       }
//   
//       public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
//           return new static(
//               $entity_type,
//               $container->get('entity_type.manager')->getStorage($entity_type->id()),
//               $container->get('entity_type.manager'),
//               $container->get('module_handler'),
//               $container->get('string_translation'),
//               $container->get('entity_field.manager'),
//           );
//       }
//   }
//
// After:
//   class MyNodeViewsData extends NodeViewsData {
//       public function __construct(
//           EntityTypeInterface $entity_type,
//           SqlEntityStorageInterface $storage_controller,
//           EntityTypeManagerInterface $entity_type_manager,
//           ModuleHandlerInterface $module_handler,
//           TranslationInterface $translation_manager,
//           EntityFieldManagerInterface $entity_field_manager,
//           ?\Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList = NULL,
//       ) {
//           parent::__construct($entity_type, $storage_controller, $entity_type_manager, $module_handler, $translation_manager, $entity_field_manager, $moduleExtensionList);
//       }
//   
//       public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
//           return new static(
//               $entity_type,
//               $container->get('entity_type.manager')->getStorage($entity_type->id()),
//               $container->get('entity_type.manager'),
//               $container->get('module_handler'),
//               $container->get('string_translation'),
//               $container->get('entity_field.manager'),
//               $container->get('extension.list.module'),
//           );
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Fixes NodeViewsData subclasses whose __construct() omits the $moduleExtensionList
 * argument added in Drupal 11.2.0 (deprecated; required in 12.0.0).
 *
 * @see https://www.drupal.org/node/3493129
 */
final class AddModuleExtensionListToNodeViewsDataConstructRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add $moduleExtensionList parameter to NodeViewsData subclass constructors and pass it to parent::__construct()',
            [
                new CodeSample(
                    <<<'CODE'
class MyNodeViewsData extends NodeViewsData {
    public function __construct(
        EntityTypeInterface $entity_type,
        SqlEntityStorageInterface $storage_controller,
        EntityTypeManagerInterface $entity_type_manager,
        ModuleHandlerInterface $module_handler,
        TranslationInterface $translation_manager,
        EntityFieldManagerInterface $entity_field_manager,
    ) {
        parent::__construct($entity_type, $storage_controller, $entity_type_manager, $module_handler, $translation_manager, $entity_field_manager);
    }
}
CODE,
                    <<<'CODE'
class MyNodeViewsData extends NodeViewsData {
    public function __construct(
        EntityTypeInterface $entity_type,
        SqlEntityStorageInterface $storage_controller,
        EntityTypeManagerInterface $entity_type_manager,
        ModuleHandlerInterface $module_handler,
        TranslationInterface $translation_manager,
        EntityFieldManagerInterface $entity_field_manager,
        ?\Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList = NULL,
    ) {
        parent::__construct($entity_type, $storage_controller, $entity_type_manager, $module_handler, $translation_manager, $entity_field_manager, $moduleExtensionList);
    }
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
        // Only process classes that extend NodeViewsData.
        if ($node->extends === null) {
            return null;
        }

        $extendsName = $this->getName($node->extends);
        if ($extendsName === null) {
            return null;
        }

        // Match short name or FQN.
        if ($extendsName !== 'NodeViewsData'
            && !str_ends_with($extendsName, '\\NodeViewsData')
        ) {
            return null;
        }

        $changed = false;

        foreach ($node->getMethods() as $method) {
            $methodName = $this->getName($method);

            if ($methodName === '__construct') {
                $changed = $this->fixConstructMethod($method) || $changed;
            }

            if ($methodName === 'createInstance') {
                $changed = $this->fixCreateInstanceMethod($method) || $changed;
            }
        }

        return $changed ? $node : null;
    }

    /**
     * Adds $moduleExtensionList parameter and passes it to parent::__construct().
     */
    private function fixConstructMethod(ClassMethod $method): bool
    {
        // Already has 7+ params – already fixed or custom.
        if (count($method->params) >= 7) {
            // Still check if parent::__construct() needs the 7th arg.
            return $this->fixParentConstructCall($method);
        }

        // Only act when the constructor has exactly 6 params (the original 6
        // EntityViewsData params, without $moduleExtensionList).
        if (count($method->params) !== 6) {
            return false;
        }

        // Add nullable $moduleExtensionList parameter with NULL default.
        $nullableType = new NullableType(new Name\FullyQualified('Drupal\Core\Extension\ModuleExtensionList'));
        $param = new Param(
            new Variable('moduleExtensionList'),
            new Node\Expr\ConstFetch(new Name('NULL')),
            $nullableType,
            false,
            false,
            [],
            0,
            []
        );
        $method->params[] = $param;

        return $this->fixParentConstructCall($method);
    }

    /**
     * Finds parent::__construct() calls with 6 args and appends $moduleExtensionList.
     */
    private function fixParentConstructCall(ClassMethod $method): bool
    {
        if ($method->stmts === null) {
            return false;
        }

        $traverser = new NodeTraverser();
        $visitor = new class(false) extends NodeVisitorAbstract {
            public bool $modified = false;

            public function __construct(bool $initial)
            {
                $this->modified = $initial;
            }

            public function enterNode(Node $node): ?Node
            {
                if (!$node instanceof StaticCall) {
                    return null;
                }

                if (!$node->class instanceof Name) {
                    return null;
                }

                if ($node->class->toString() !== 'parent') {
                    return null;
                }

                if (!$node->name instanceof Node\Identifier) {
                    return null;
                }

                if ($node->name->toString() !== '__construct') {
                    return null;
                }

                // Only fix if exactly 6 args (the original 6, missing $moduleExtensionList).
                if (count($node->args) !== 6) {
                    return null;
                }

                $node->args[] = new Arg(new Variable('moduleExtensionList'));
                $this->modified = true;

                return $node;
            }
        };

        $traverser->addVisitor($visitor);
        $traverser->traverse($method->stmts);

        return $visitor->modified;
    }

    /**
     * In createInstance, fixes new static(...) calls that omit extension.list.module.
     */
    private function fixCreateInstanceMethod(ClassMethod $method): bool
    {
        if ($method->stmts === null) {
            return false;
        }

        $traverser = new NodeTraverser();
        $visitor = new class(false) extends NodeVisitorAbstract {
            public bool $modified = false;

            public function __construct(bool $initial)
            {
                $this->modified = $initial;
            }

            public function enterNode(Node $node): ?Node
            {
                if (!$node instanceof Node\Expr\New_) {
                    return null;
                }

                // Match new static(...) with exactly 6 args.
                if (!$node->class instanceof Name || $node->class->toString() !== 'static') {
                    return null;
                }

                if (count($node->args) !== 6) {
                    return null;
                }

                // Append $container->get('extension.list.module') as 7th arg.
                $containerGet = new Node\Expr\MethodCall(
                    new Variable('container'),
                    'get',
                    [new Arg(new String_('extension.list.module'))]
                );
                $node->args[] = new Arg($containerGet);
                $this->modified = true;

                return $node;
            }
        };

        $traverser->addVisitor($visitor);
        $traverser->traverse($method->stmts);

        return $visitor->modified;
    }
}
