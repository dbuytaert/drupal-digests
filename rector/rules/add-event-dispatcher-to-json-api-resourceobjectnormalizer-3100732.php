<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3100732
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Targets subclasses of
// Drupal\jsonapi\Normalizer\ResourceObjectNormalizer or
// Drupal\jsonapi\Controller\EntityResource that override __construct and
// call parent::__construct() without the new $event_dispatcher argument.
// Adds ?EventDispatcherInterface $event_dispatcher = NULL to the method
// signature and forwards it to the parent call. The omission was
// deprecated in drupal:11.2.0 and the argument will be required in
// drupal:12.0.0.
//
// Before:
//   use Drupal\jsonapi\Normalizer\ResourceObjectNormalizer;
//   use Drupal\jsonapi\EventSubscriber\ResourceObjectNormalizationCacher;
//   
//   class MyNormalizer extends ResourceObjectNormalizer {
//       public function __construct(ResourceObjectNormalizationCacher $cacher) {
//           parent::__construct($cacher);
//       }
//   }
//
// After:
//   use Drupal\jsonapi\Normalizer\ResourceObjectNormalizer;
//   use Drupal\jsonapi\EventSubscriber\ResourceObjectNormalizationCacher;
//   
//   class MyNormalizer extends ResourceObjectNormalizer {
//       public function __construct(ResourceObjectNormalizationCacher $cacher, ?\Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher = NULL) {
//           parent::__construct($cacher, $event_dispatcher);
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Arg;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the missing $event_dispatcher argument to constructors of classes
 * that extend ResourceObjectNormalizer or EntityResource (JSON:API) and call
 * parent::__construct() without the new required parameter, which was
 * deprecated in drupal:11.2.0 and will be required in drupal:12.0.0.
 */
final class AddEventDispatcherToJsonApiConstructorRector extends AbstractRector
{
    private const TARGET_SHORT_NAMES = [
        'ResourceObjectNormalizer',
        'EntityResource',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing $event_dispatcher argument to parent::__construct() calls in subclasses of JSON:API ResourceObjectNormalizer or EntityResource (deprecated in drupal:11.2.0, required in drupal:12.0.0).',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\jsonapi\Normalizer\ResourceObjectNormalizer;
use Drupal\jsonapi\EventSubscriber\ResourceObjectNormalizationCacher;

class MyNormalizer extends ResourceObjectNormalizer {
    public function __construct(ResourceObjectNormalizationCacher $cacher) {
        parent::__construct($cacher);
    }
}
CODE
                    ,
                    <<<'CODE'
use Drupal\jsonapi\Normalizer\ResourceObjectNormalizer;
use Drupal\jsonapi\EventSubscriber\ResourceObjectNormalizationCacher;

class MyNormalizer extends ResourceObjectNormalizer {
    public function __construct(ResourceObjectNormalizationCacher $cacher, ?\Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher = NULL) {
        parent::__construct($cacher, $event_dispatcher);
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
        if ($node->extends === null) {
            return null;
        }

        $parentClassName = $this->getName($node->extends);
        if ($parentClassName === null) {
            return null;
        }

        $shortName = substr($parentClassName, (int) strrpos($parentClassName, '\\') + 1);
        if (!in_array($shortName, self::TARGET_SHORT_NAMES, true)) {
            return null;
        }

        $constructMethod = null;
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $this->isName($stmt, '__construct')) {
                $constructMethod = $stmt;
                break;
            }
        }

        if ($constructMethod === null) {
            return null;
        }

        foreach ($constructMethod->params as $param) {
            if ($this->isName($param->var, 'event_dispatcher')) {
                return null;
            }
        }

        $parentCall = null;
        $this->traverseNodesWithCallable((array) $constructMethod->stmts, function (Node $n) use (&$parentCall): void {
            if (
                $n instanceof StaticCall
                && $this->isName($n->class, 'parent')
                && $this->isName($n->name, '__construct')
            ) {
                $parentCall = $n;
            }
        });

        if (!$parentCall instanceof StaticCall) {
            return null;
        }

        foreach ($parentCall->args as $arg) {
            if ($arg instanceof Arg && $arg->value instanceof Variable && $this->isName($arg->value, 'event_dispatcher')) {
                return null;
            }
        }

        $constructMethod->params[] = new Param(
            var: new Variable('event_dispatcher'),
            default: new ConstFetch(new Name('NULL')),
            type: new NullableType(new Name\FullyQualified('Symfony\Component\EventDispatcher\EventDispatcherInterface'))
        );

        $parentCall->args[] = new Arg(new Variable('event_dispatcher'));

        return $node;
    }
}
