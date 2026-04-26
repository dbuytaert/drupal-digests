<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3093118
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes direct calls to LinkWidget::validateTitleElement(), deprecated
// in drupal:11.4.0 and removed in drupal:12.0.0. The link-title-required
// validation is now handled at the field level by
// LinkTitleRequiredConstraint on the LinkItem field type, so explicit
// form-level calls to this method are no longer needed.
//
// Before:
//   LinkWidget::validateTitleElement($element, $form_state, $form);
//
// After:
//   // Removed: validation is now handled by LinkTitleRequiredConstraint on LinkItem.


use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveLinkWidgetValidateTitleElementRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to deprecated LinkWidget::validateTitleElement(), deprecated in drupal:11.4.0. The title-required validation is now handled by the LinkTitleRequiredConstraint on the LinkItem field type.',
            [
                new CodeSample(
                    'LinkWidget::validateTitleElement($element, $form_state, $form);',
                    '// Removed: validation is now handled by LinkTitleRequiredConstraint.'
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
    public function refactor(Node $node): Node|int|null
    {
        if (!$node->expr instanceof StaticCall) {
            return null;
        }

        $staticCall = $node->expr;

        if (!$this->isName($staticCall->name, 'validateTitleElement')) {
            return null;
        }

        if (!$this->isName($staticCall->class, 'Drupal\\link\\Plugin\\Field\\FieldWidget\\LinkWidget')) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
