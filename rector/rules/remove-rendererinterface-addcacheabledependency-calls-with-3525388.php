<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3525388
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Detects calls to RendererInterface::addCacheableDependency($elements,
// $dependency) where the $dependency argument is provably not an object
// (bool, int, string, null, or array) and therefore can never implement
// CacheableDependencyInterface. Passing such values is deprecated in
// Drupal 11.3 and will throw in Drupal 13.0; it also silently sets max-
// age 0, making pages uncacheable. Removes the offending statement.
//
// Before:
//   $this->renderer->addCacheableDependency($build, false);


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes calls to RendererInterface::addCacheableDependency() where the
 * second argument is provably not a CacheableDependencyInterface instance
 * (bool, int, string, null, or array). Passing such values triggers a
 * deprecation in Drupal 11.3 and will throw in Drupal 13.0, and silently
 * sets max-age 0 on the render array, making pages uncacheable.
 *
 * @see https://www.drupal.org/project/drupal/issues/3525388
 * @see https://www.drupal.org/node/3525389
 */
final class RemoveRendererAddCacheableDependencyNonObjectRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to RendererInterface::addCacheableDependency() where the dependency argument cannot implement CacheableDependencyInterface (bool, int, string, null, array). Such calls silently make pages uncacheable and are deprecated in Drupal 11.3.',
            [
                new CodeSample(
                    '$this->renderer->addCacheableDependency($build, false);',
                    '',
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        // Match at statement level so we can remove the whole expression.
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): ?int
    {
        $expr = $node->expr;

        // We only care about method calls.
        if (!$expr instanceof MethodCall) {
            return null;
        }

        // Method must be named addCacheableDependency.
        if (!$this->isName($expr->name, 'addCacheableDependency')) {
            return null;
        }

        // RendererInterface::addCacheableDependency takes exactly 2 arguments:
        // (array &$elements, $dependency). This distinguishes it from
        // RefinableCacheableDependencyInterface::addCacheableDependency (1 arg).
        if (count($expr->args) !== 2) {
            return null;
        }

        // The callee must be typed as RendererInterface (or a subclass/
        // implementation such as Renderer itself).
        if (!$this->isObjectType($expr->var, new ObjectType('Drupal\\Core\\Render\\RendererInterface'))) {
            return null;
        }

        // Resolve the PHPStan type of the second argument (the dependency).
        $dependencyArg = $expr->args[1]->value;
        $dependencyType = $this->getType($dependencyArg);

        // isObject()->no() is true for bool, int, float, string, null, array -
        // any type that is definitively NOT an object and therefore can never
        // implement CacheableDependencyInterface.
        if (!$dependencyType->isObject()->no()) {
            return null;
        }

        // Remove the whole expression statement.
        return NodeVisitor::REMOVE_NODE;
    }
}
