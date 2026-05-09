<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated
 * EntityTypeInterface::getOriginalClass() with the semantically
 * equivalent getDecoratedClasses()[0] ?? $entityType->getClass().
 * getOriginalClass() is deprecated in Drupal 11.4.0 and removed in
 * 12.0.0. Because getDecoratedClasses() returns an array (not a string),
 * the replacement expression recovers the original class name string
 * while retaining the same fallback-to-current-class behaviour.
 *
 * Before:
 *   $original = $entityType->getOriginalClass();
 *
 * After:
 *   $original = $entityType->getDecoratedClasses()[0] ?? $entityType->getClass();
 *
 * Caveats:
 *   Only transforms calls where the receiver is a simple variable (e.g.
 *   $entityType->getOriginalClass()). Calls on complex expressions such
 *   as $manager->getDefinition('node')->getOriginalClass() are
 *   intentionally skipped to avoid evaluating the receiver expression
 *   twice (potential side effects).
 *
 * @see https://www.drupal.org/node/3557461
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\LNumber;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GetOriginalClassToGetDecoratedClassesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace EntityTypeInterface::getOriginalClass() with getDecoratedClasses()[0] ?? $entityType->getClass()',
            [new CodeSample(
                '$entityType->getOriginalClass();',
                '$entityType->getDecoratedClasses()[0] ?? $entityType->getClass();',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($node->name, 'getOriginalClass')) {
            return null;
        }
        if (!$this->isObjectType($node->var, new ObjectType('Drupal\Core\Entity\EntityTypeInterface'))) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }
        // Only transform when receiver is a simple variable to avoid
        // evaluating complex expressions (with potential side effects) twice.
        if (!$node->var instanceof Variable) {
            return null;
        }

        // Build: $entityType->getDecoratedClasses()[0] ?? $entityType->getClass()
        $getDecoratedClasses = new MethodCall($node->var, 'getDecoratedClasses');
        $arrayDimFetch = new ArrayDimFetch($getDecoratedClasses, new LNumber(0));
        $getClass = new MethodCall($node->var, 'getClass');

        return new Coalesce($arrayDimFetch, $getClass);
    }
}
