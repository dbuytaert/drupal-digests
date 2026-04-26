<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3535526
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to block_content_add_body_field(), deprecated in
// drupal:11.3.0 and removed in drupal:12.0.0 with no replacement. Body
// fields for block content types should be managed via exported
// configuration rather than procedural code. Blocks have no
// teaser/summary view mode, making text_with_summary fields semantically
// inappropriate.
//
// Before:
//   block_content_add_body_field($block_type_id, $label);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated block_content_add_body_field() function call statements.
 *
 * Deprecated in drupal:11.3.0, removed in drupal:12.0.0 with no replacement.
 * Body fields should be managed via exported config rather than procedural code.
 *
 * @see https://www.drupal.org/node/3535528
 */
final class RemoveBlockContentAddBodyFieldRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated block_content_add_body_field() calls. Deprecated in drupal:11.3.0 and removed in drupal:12.0.0 with no replacement.',
            [
                new CodeSample(
                    'block_content_add_body_field($block_type_id, $label);',
                    ''
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): ?int
    {
        $expr = $node->expr;
        if (!$expr instanceof FuncCall) {
            return null;
        }

        if (!$this->isName($expr->name, 'block_content_add_body_field')) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
