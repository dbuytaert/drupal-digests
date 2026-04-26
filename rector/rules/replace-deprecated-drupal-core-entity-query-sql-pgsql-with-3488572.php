<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3488572
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecated Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory
// and Drupal\Core\Entity\Query\Sql\pgsql\Condition, moving them to the
// pgsql module under Drupal\pgsql\EntityQuery. This rule rewrites use
// imports and inline fully-qualified references so contrib and custom
// code targets the new, non-deprecated classes before Drupal 12.0
// removes the old ones.
//
// Before:
//   use Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory;
//   
//   class MyQueryFactory extends QueryFactory {
//   }
//
// After:
//   use Drupal\pgsql\EntityQuery\QueryFactory;
//   
//   class MyQueryFactory extends QueryFactory {
//   }


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Use_;
use Rector\Config\RectorConfig;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated Drupal\Core\Entity\Query\Sql\pgsql\* classes with
 * Drupal\pgsql\EntityQuery\* equivalents introduced in drupal:11.2.0.
 */
final class RenamePgsqlEntityQueryClassesRector extends AbstractRector
{
    private const CLASS_MAP = [
        'Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory' => 'Drupal\pgsql\EntityQuery\QueryFactory',
        'Drupal\Core\Entity\Query\Sql\pgsql\Condition'    => 'Drupal\pgsql\EntityQuery\Condition',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Drupal\Core\Entity\Query\Sql\pgsql\* with Drupal\pgsql\EntityQuery\*',
            [
                new CodeSample(
                    'use Drupal\Core\Entity\Query\Sql\pgsql\QueryFactory;',
                    'use Drupal\pgsql\EntityQuery\QueryFactory;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Use_::class, FullyQualified::class];
    }

    /** @param Use_|FullyQualified $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Use_) {
            return $this->refactorUse($node);
        }
        return $this->refactorFullyQualified($node);
    }

    private function refactorUse(Use_ $node): ?Use_
    {
        $changed = false;
        foreach ($node->uses as $use) {
            $fqcn = $use->name->toString();
            if (isset(self::CLASS_MAP[$fqcn])) {
                $use->name = new Name(self::CLASS_MAP[$fqcn]);
                $changed = true;
            }
        }
        return $changed ? $node : null;
    }

    private function refactorFullyQualified(FullyQualified $node): ?FullyQualified
    {
        $fqcn = $node->toString();
        if (!isset(self::CLASS_MAP[$fqcn])) {
            return null;
        }

        // If the original source name (before use-alias resolution) is shorter
        // than the FQCN, the node came from a use-alias import. The Use_ branch
        // will rewrite the import; skip here to avoid emitting a redundant FQCN.
        $originalName = $node->getAttribute(AttributeKey::ORIGINAL_NAME);
        if ($originalName instanceof Node && $originalName->toString() !== $fqcn) {
            return null;
        }

        return new FullyQualified(self::CLASS_MAP[$fqcn]);
    }
}
