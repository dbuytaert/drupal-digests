<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3488467
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 introduced StatementBase as a common parent for
// StatementWrapperIterator and StatementPrefetchIterator. The
// $defaultFetchMode property is deprecated in both classes in favour of
// $fetchMode provided by StatementBase, and will be removed in Drupal
// 12. This rule rewrites $this->defaultFetchMode to $this->fetchMode in
// any class that directly extends one of those three statement classes.
//
// Before:
//   $mode = $this->defaultFetchMode;
//
// After:
//   $mode = $this->fetchMode;


use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated $defaultFetchMode with $fetchMode in statement classes.
 *
 * Both StatementWrapperIterator and StatementPrefetchIterator now extend
 * StatementBase, which provides $fetchMode as the canonical property.
 * The $defaultFetchMode property is deprecated in drupal:11.2.0 and removed
 * in drupal:12.0.0.
 */
final class ReplaceDefaultFetchModeWithFetchModeRector extends AbstractRector
{
    /**
     * Short names of classes that are or extend the statement hierarchy.
     *
     * @var list<string>
     */
    private const STATEMENT_PARENTS = [
        'StatementBase',
        'StatementWrapperIterator',
        'StatementPrefetchIterator',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated $defaultFetchMode property access with $fetchMode in classes extending StatementBase, StatementWrapperIterator, or StatementPrefetchIterator.',
            [
                new CodeSample(
                    '$mode = $this->defaultFetchMode;',
                    '$mode = $this->fetchMode;'
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
        // Only apply to classes extending a known statement parent (by short name).
        if (!$node->extends instanceof Name) {
            return null;
        }

        $parentShortName = $node->extends->getLast();
        if (!in_array($parentShortName, self::STATEMENT_PARENTS, true)) {
            return null;
        }

        // Find all $this->defaultFetchMode accesses in this class body.
        $nodeFinder = new NodeFinder();
        $propertyFetches = $nodeFinder->findInstanceOf($node->stmts, PropertyFetch::class);

        $changed = false;
        foreach ($propertyFetches as $propertyFetch) {
            if (!$propertyFetch->var instanceof Variable) {
                continue;
            }
            if ($this->getName($propertyFetch->var) !== 'this') {
                continue;
            }
            if ($this->getName($propertyFetch->name) !== 'defaultFetchMode') {
                continue;
            }
            $propertyFetch->name = new Identifier('fetchMode');
            $changed = true;
        }

        return $changed ? $node : null;
    }
}
