<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571065
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the deprecated $entity->original magic property, removed in
// drupal:12.0.0 (deprecated drupal:11.2.0, issue #3571065). Read
// accesses become $entity->getOriginal() and write assignments become
// $entity->setOriginal($value). Skips $this->original to avoid false
// positives on non-entity classes such as EntityTypeEvent and
// FieldStorageDefinitionEvent that have a legitimate $original property.
//
// Before:
//   $entity->original->field->value;
//   $entity->original = $unchanged;
//
// After:
//   $entity->getOriginal()->field->value;
//   $entity->setOriginal($unchanged);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated $entity->original magic property with method calls.
 *
 * The magic property was deprecated in drupal:11.2.0 and removed in
 * drupal:12.0.0 (issue #3571065). The __get/__set/__isset/__unset handlers
 * that forwarded ->original to getOriginal()/setOriginal() were removed from
 * EntityBase.
 *
 * Transformations:
 *   $entity->original           => $entity->getOriginal()
 *   $entity->original = $value  => $entity->setOriginal($value)
 *
 * The rule skips $this->original accesses because several non-entity classes
 * (e.g. EntityTypeEvent, FieldStorageDefinitionEvent) have a legitimate
 * protected $original property that must not be rewritten.
 *
 * The rule works bottom-up: PropertyFetch 'original' is first rewritten to
 * getOriginal(), then any Assign whose LHS became getOriginal() (an otherwise
 * invalid PHP assignment target) is rewritten to setOriginal().
 *
 * @see https://www.drupal.org/node/3295826
 * @see https://www.drupal.org/project/drupal/issues/3571065
 */
final class EntityOriginalPropertyToMethodRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated $entity->original magic property with getOriginal()/setOriginal() method calls (removed in drupal:12.0.0)',
            [
                new CodeSample(
                    '$original = $entity->original;',
                    '$original = $entity->getOriginal();'
                ),
                new CodeSample(
                    '$entity->original = $unchanged;',
                    '$entity->setOriginal($unchanged);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, Assign::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Step 1 (bottom-up, visits children first):
        // $entity->original  =>  $entity->getOriginal()
        //
        // Skip $this->original: non-entity classes (EntityTypeEvent,
        // FieldStorageDefinitionEvent, etc.) have a legitimate $original
        // property that must not be rewritten.
        if ($node instanceof PropertyFetch) {
            if ($this->isName($node->name, 'original')
                && !$this->isThisVar($node->var)
            ) {
                return new MethodCall($node->var, 'getOriginal');
            }
            return null;
        }

        // Step 2 (after step 1 has transformed the LHS PropertyFetch):
        // The assignment $entity->original = $x has its LHS already transformed
        // to $entity->getOriginal() (which is an invalid PHP assignment target).
        // Detect that intermediate state and rewrite to $entity->setOriginal($x).
        if ($node instanceof Assign
            && $node->var instanceof MethodCall
            && $this->isName($node->var->name, 'getOriginal')
            && empty($node->var->args)
        ) {
            return new MethodCall(
                $node->var->var,
                'setOriginal',
                [new Arg($node->expr)]
            );
        }

        return null;
    }

    /**
     * Returns true when the node is the $this variable.
     */
    private function isThisVar(Node $node): bool
    {
        return $node instanceof Variable && $node->name === 'this';
    }
}
