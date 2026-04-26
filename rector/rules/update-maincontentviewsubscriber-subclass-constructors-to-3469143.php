<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3469143
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.x, MainContentViewSubscriber::__construct() changed from
// (ClassResolverInterface, RouteMatchInterface, array) to
// (RouteMatchInterface, ServiceLocator). This rule updates subclass
// constructors that forward those arguments: it removes the
// ClassResolverInterface parameter, converts the array
// $mainContentRenderers parameter to ServiceLocator with the
// #[AutowireLocator('render.main_content_renderer', 'format')]
// attribute, and rewrites the parent::__construct() call to match.
//
// Before:
//   use Drupal\Core\DependencyInjection\ClassResolverInterface;
//   use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
//   use Drupal\Core\Routing\RouteMatchInterface;
//   
//   class MySubscriber extends MainContentViewSubscriber {
//       public function __construct(
//           ClassResolverInterface $classResolver,
//           RouteMatchInterface $routeMatch,
//           array $mainContentRenderers,
//       ) {
//           parent::__construct($classResolver, $routeMatch, $mainContentRenderers);
//       }
//   }
//
// After:
//   use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
//   use Drupal\Core\Routing\RouteMatchInterface;
//   use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
//   use Symfony\Component\DependencyInjection\ServiceLocator;
//   
//   class MySubscriber extends MainContentViewSubscriber {
//       public function __construct(
//           RouteMatchInterface $routeMatch,
//           #[AutowireLocator('render.main_content_renderer', 'format')]
//           ServiceLocator $mainContentRenderers,
//       ) {
//           parent::__construct($routeMatch, $mainContentRenderers);
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class UpdateMainContentViewSubscriberConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Update parent::__construct() in MainContentViewSubscriber subclasses: remove ClassResolverInterface param and convert array renderers param to ServiceLocator',
            [
                new CodeSample(
                    <<<'PHP'
use Drupal\Core\DependencyInjection\ClassResolverInterface;
use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
use Drupal\Core\Routing\RouteMatchInterface;

class MySubscriber extends MainContentViewSubscriber {
    public function __construct(
        ClassResolverInterface $classResolver,
        RouteMatchInterface $routeMatch,
        array $mainContentRenderers,
    ) {
        parent::__construct($classResolver, $routeMatch, $mainContentRenderers);
    }
}
PHP,
                    <<<'PHP'
use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

class MySubscriber extends MainContentViewSubscriber {
    public function __construct(
        RouteMatchInterface $routeMatch,
        #[AutowireLocator('render.main_content_renderer', 'format')]
        ServiceLocator $mainContentRenderers,
    ) {
        parent::__construct($routeMatch, $mainContentRenderers);
    }
}
PHP
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
        if ($node->extends === null) {
            return null;
        }

        if ($node->extends->getLast() !== 'MainContentViewSubscriber') {
            return null;
        }

        $constructMethod = null;
        foreach ($node->getMethods() as $method) {
            if ($this->isName($method->name, '__construct')) {
                $constructMethod = $method;
                break;
            }
        }

        if ($constructMethod === null) {
            return null;
        }

        $parentCall = $this->findParentConstructCall($constructMethod);
        if ($parentCall === null || count($parentCall->args) !== 3) {
            return null;
        }

        $arg0 = $parentCall->args[0];
        $arg2 = $parentCall->args[2];

        if (!$arg0 instanceof Arg || !$arg2 instanceof Arg) {
            return null;
        }

        if (!$arg0->value instanceof Variable || !$arg2->value instanceof Variable) {
            return null;
        }

        $classResolverVarName = $arg0->value->name;
        $arrayVarName = $arg2->value->name;

        $classResolverParamIndex = null;
        $arrayParamIndex = null;

        foreach ($constructMethod->params as $index => $param) {
            if (!$param->var instanceof Variable) {
                continue;
            }

            if ($param->var->name === $classResolverVarName && $this->isClassResolverInterfaceType($param->type)) {
                $classResolverParamIndex = $index;
            }

            if ($param->var->name === $arrayVarName && $this->isArrayType($param->type)) {
                $arrayParamIndex = $index;
            }
        }

        if ($classResolverParamIndex === null || $arrayParamIndex === null) {
            return null;
        }

        $arrayParam = $constructMethod->params[$arrayParamIndex];
        $arrayParam->type = new FullyQualified('Symfony\Component\DependencyInjection\ServiceLocator');
        $arrayParam->attrGroups[] = $this->createAutoWireLocatorAttributeGroup();

        unset($constructMethod->params[$classResolverParamIndex]);
        $constructMethod->params = array_values($constructMethod->params);

        array_splice($parentCall->args, 0, 1);

        return $node;
    }

    private function findParentConstructCall(ClassMethod $classMethod): ?StaticCall
    {
        $parentCall = null;
        $this->traverseNodesWithCallable((array) $classMethod->stmts, function (Node $node) use (&$parentCall): void {
            if (
                $node instanceof StaticCall
                && $node->class instanceof Name
                && $this->isName($node->class, 'parent')
                && $this->isName($node->name, '__construct')
            ) {
                $parentCall = $node;
            }
        });

        return $parentCall;
    }

    private function isClassResolverInterfaceType(?Node $type): bool
    {
        if ($type instanceof Name) {
            return str_ends_with($type->toString(), 'ClassResolverInterface');
        }

        return false;
    }

    private function isArrayType(?Node $type): bool
    {
        if ($type instanceof Identifier) {
            return $type->name === 'array';
        }

        return false;
    }

    private function createAutoWireLocatorAttributeGroup(): AttributeGroup
    {
        $attribute = new Attribute(
            new FullyQualified('Symfony\Component\DependencyInjection\Attribute\AutowireLocator'),
            [
                new Arg(new String_('render.main_content_renderer')),
                new Arg(new String_('format')),
            ]
        );

        return new AttributeGroup([$attribute]);
    }
}
