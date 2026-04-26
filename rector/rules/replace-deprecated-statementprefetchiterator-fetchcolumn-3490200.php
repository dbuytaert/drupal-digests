<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3490200
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Renames StatementPrefetchIterator::fetchColumn() to fetchField(), as
// fetchColumn() was deprecated in Drupal 11.2.0 and will be removed in
// 12.0.0. The two methods are functionally identical; the rename aligns
// Drupal's API with its own naming convention and removes the legacy
// PDO-style alias. Skips calls on $this->clientStatement to avoid
// touching PDO's native fetchColumn().
//
// Before:
//   $name = $statement->fetchColumn(0);
//
// After:
//   $name = $statement->fetchField(0);


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated StatementPrefetchIterator::fetchColumn() with fetchField().
 */
final class StatementPrefetchIteratorFetchColumnRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated StatementPrefetchIterator::fetchColumn() with fetchField()',
            [
                new CodeSample(
                    '$result = $statement->fetchColumn(0);',
                    '$result = $statement->fetchField(0);',
                ),
            ]
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
        if (!$this->isName($node->name, 'fetchColumn')) {
            return null;
        }

        // Skip PDO's fetchColumn() called on $this->clientStatement or similar
        // PDO statement properties, to avoid altering non-Drupal uses.
        if ($node->var instanceof \PhpParser\Node\Expr\PropertyFetch) {
            $propertyName = $this->getName($node->var->name);
            if ($propertyName === 'clientStatement') {
                return null;
            }
        }

        $node->name = new \PhpParser\Node\Identifier('fetchField');
        return $node;
    }
}
