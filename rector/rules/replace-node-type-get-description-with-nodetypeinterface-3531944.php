<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3531944
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated procedural function
// node_type_get_description($node_type) with the OOP equivalent
// $node_type->getDescription(). The function was deprecated in Drupal
// 11.x (issue #3531944) with no direct API replacement because
// NodeTypeInterface::getDescription() already provides the same result.
// This rule keeps code up to date before the function is removed in
// Drupal 13.0.0.
//
// Before:
//   $desc = node_type_get_description($node_type);
//
// After:
//   $desc = $node_type->getDescription();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated node_type_get_description($node_type) with
 * $node_type->getDescription().
 */
final class NodeTypeGetDescriptionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_type_get_description($node_type) with $node_type->getDescription()',
            [
                new CodeSample(
                    'node_type_get_description($node_type);',
                    '$node_type->getDescription();'
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
        if (!$this->isName($node->name, 'node_type_get_description')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) !== 1) {
            return null;
        }

        return new MethodCall($args[0]->value, 'getDescription');
    }
}
