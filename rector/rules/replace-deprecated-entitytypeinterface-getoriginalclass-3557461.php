<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to EntityTypeInterface::getOriginalClass(), deprecated
 * in drupal:11.4.0 and removed in drupal:12.0.0, with
 * getDecoratedClasses()[0]. The deprecated method returned the initial
 * class name as a string; the replacement returns the full decoration
 * chain as an array, so the first element ([0]) is the closest automated
 * equivalent.
 *
 * Before:
 *   $originalClass = $entityType->getOriginalClass();
 *
 * After:
 *   $originalClass = $entityType->getDecoratedClasses()[0];
 *
 * Caveats:
 *   When an entity type has never been decorated (no setClass() call
 *   was ever made), getDecoratedClasses() returns an empty array, so
 *   [0] yields null rather than the current class string that
 *   getOriginalClass() would have returned. In that case callers should
 *   use getClass() instead. The rule only fires on receivers typed as
 *   Drupal\Core\Entity\EntityTypeInterface; untyped variables are not
 *   rewritten.
 *
 * @see https://www.drupal.org/node/3557461
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\Int_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GetOriginalClassToGetDecoratedClassesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated EntityTypeInterface::getOriginalClass() with getDecoratedClasses()[0].',
            [new CodeSample(
                '$originalClass = $entityType->getOriginalClass();',
                '$originalClass = $entityType->getDecoratedClasses()[0];',
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
        if (!$this->isName($node->name, 'getOriginalClass')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new \PHPStan\Type\ObjectType('Drupal\Core\Entity\EntityTypeInterface'))) {
            return null;
        }

        $newMethodCall = new MethodCall($node->var, 'getDecoratedClasses', []);
        return new ArrayDimFetch($newMethodCall, new Int_(0));
    }
}
