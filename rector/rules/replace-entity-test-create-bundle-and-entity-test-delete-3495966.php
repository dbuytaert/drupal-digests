<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3495966
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated procedural functions
// entity_test_create_bundle() and entity_test_delete_bundle() with their
// static equivalents EntityTestHelper::createBundle() and
// EntityTestHelper::deleteBundle(). Both functions were deprecated in
// Drupal 11.2.0 and will be removed in Drupal 12.0.0. The entity_test
// module is widely used as a test dependency throughout contrib, so this
// rule is broadly applicable.
//
// Before:
//   entity_test_create_bundle($bundle, $text, $entity_type);
//   entity_test_delete_bundle($bundle, $entity_type);
//
// After:
//   \Drupal\entity_test\EntityTestHelper::createBundle($bundle, $text, $entity_type);
//   \Drupal\entity_test\EntityTestHelper::deleteBundle($bundle, $entity_type);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EntityTestBundleFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated entity_test_create_bundle() and entity_test_delete_bundle() with EntityTestHelper static methods.',
            [
                new CodeSample(
                    'entity_test_create_bundle($bundle, $text, $entity_type);',
                    '\\Drupal\\entity_test\\EntityTestHelper::createBundle($bundle, $text, $entity_type);'
                ),
                new CodeSample(
                    'entity_test_delete_bundle($bundle, $entity_type);',
                    '\\Drupal\\entity_test\\EntityTestHelper::deleteBundle($bundle, $entity_type);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->name instanceof Name) {
            return null;
        }

        $funcName = $this->getName($node->name);

        if ($funcName === 'entity_test_create_bundle') {
            $method = 'createBundle';
        } elseif ($funcName === 'entity_test_delete_bundle') {
            $method = 'deleteBundle';
        } else {
            return null;
        }

        return new StaticCall(
            new FullyQualified('Drupal\\entity_test\\EntityTestHelper'),
            $method,
            $node->args
        );
    }
}
