<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * The protected TypedDataManager::$prototypes cache is now a \WeakMap
 * keyed by data definition. Assigning an array to it is deprecated and
 * becomes a TypeError in Drupal 13. This rule rewrites statements in
 * TypedDataManager subclasses that reset the property with an empty
 * array to assign a new \WeakMap instead.
 *
 * Before:
 *   $this->prototypes = [];
 *
 * After:
 *   $this->prototypes = new \WeakMap();
 *
 * Caveats:
 *   Only rewrites standalone statements assigning an empty array on a
 *   TypedDataManager receiver. Code that writes keyed entries
 *   ($this->prototypes[$key] = ...), reads the array, or redeclares the
 *   property needs manual migration to the definition-keyed WeakMap.
 *
 * @see https://www.drupal.org/node/3616328
 * @deprecated drupal:11.6.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ResetTypedDataPrototypesWithWeakMapRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace an empty array assigned to TypedDataManager::$prototypes with a \\WeakMap.',
            [new CodeSample(
                '$this->prototypes = [];',
                '$this->prototypes = new \\WeakMap();',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): ?Node
    {
        $assign = $node->expr;
        if (!$assign instanceof Assign) {
            return null;
        }
        if (!$assign->var instanceof PropertyFetch) {
            return null;
        }
        if (!$this->isName($assign->var->name, 'prototypes')) {
            return null;
        }
        if (!$assign->expr instanceof Array_ || $assign->expr->items !== []) {
            return null;
        }
        if (!$this->isObjectType($assign->var->var, new ObjectType('Drupal\\Core\\TypedData\\TypedDataManager'))) {
            return null;
        }
        $assign->expr = new New_(new FullyQualified('WeakMap'));
        return $node;
    }
}
