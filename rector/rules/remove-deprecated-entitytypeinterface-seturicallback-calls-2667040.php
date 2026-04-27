<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/2667040
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to EntityTypeInterface::setUriCallback(), deprecated in
// Drupal 11.4.0 and removed in Drupal 13.0.0. Standalone statement calls
// are deleted entirely. When setUriCallback() appears mid-chain (fluent
// style), it is removed while the rest of the chain is preserved.
// Developers must replace the callback with link templates or a route
// provider.
//
// Before:
//   $entity_types['my_entity']->setUriCallback('my_entity_uri');
//
// After:
//   // Removed: define a canonical link template or route provider instead.


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated setUriCallback() calls on entity type objects.
 *
 * EntityTypeInterface::setUriCallback() is deprecated in Drupal 11.4.0 and
 * removed in Drupal 13.0.0. Standalone statement calls are deleted entirely.
 * When setUriCallback() appears mid-chain (fluent style), the call is removed
 * by replacing it with its receiver so the rest of the chain remains intact.
 */
final class RemoveSetUriCallbackRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated EntityTypeInterface::setUriCallback() calls; use link templates or a route provider instead.',
            [
                new CodeSample(
                    '$entity_type->setUriCallback(\'my_entity_uri\');',
                    '// Removed: define a canonical link template or route provider instead.'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class, MethodCall::class];
    }

    /**
     * @param Expression|MethodCall $node
     */
    public function refactor(Node $node): int|Node|null
    {
        // Case 1: standalone statement — $entity_type->setUriCallback('func');
        if ($node instanceof Expression) {
            if (
                $node->expr instanceof MethodCall
                && $this->isName($node->expr->name, 'setUriCallback')
            ) {
                return NodeVisitor::REMOVE_NODE;
            }
            return null;
        }

        // Case 2: fluent chain — $entity_type->setUriCallback('func')->someOtherMethod()
        // Replace the setUriCallback() call with its receiver so the chain is preserved.
        if ($node instanceof MethodCall) {
            if (
                $node->var instanceof MethodCall
                && $this->isName($node->var->name, 'setUriCallback')
            ) {
                $node->var = $node->var->var;
                return $node;
            }
        }

        return null;
    }
}
