<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes calls to KernelTestBase::installSchema('system', 'sequences')
 * (and equivalent array forms). The sequences table was deprecated in
 * drupal:10.2.0 and fully removed in drupal:12.0.0; such calls now throw
 * a LogicException. When 'sequences' appears alongside other tables in
 * an array, only that entry is removed.
 *
 * Before:
 *   $this->installSchema('system', ['sequences']);
 *
 * After:
 *   // call removed
 *
 * @see https://www.drupal.org/node/3335756
 * @deprecated drupal:10.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated installSchema('system', 'sequences') calls.
 *
 * The sequences table was deprecated in drupal:10.2.0 and removed in
 * drupal:12.0.0. Calls to KernelTestBase::installSchema() targeting this
 * table now throw a LogicException and must be removed.
 */
final class RemoveInstallSchemaSystemSequencesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Remove deprecated installSchema('system', 'sequences') calls; the sequences table was removed in Drupal 12.",
            [
                new CodeSample(
                    "\$this->installSchema('system', ['sequences']);",
                    '// call removed'
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
    public function refactor(Node $node): int|Node|null
    {
        if (!$node->expr instanceof MethodCall) {
            return null;
        }

        $methodCall = $node->expr;

        if (!$this->isName($methodCall->name, 'installSchema')) {
            return null;
        }

        $args = $methodCall->args;
        if (count($args) < 2) {
            return null;
        }

        $firstArg = $args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }
        if (!$firstArg->value instanceof String_) {
            return null;
        }
        if ($firstArg->value->value !== 'system') {
            return null;
        }

        $secondArg = $args[1];
        if (!$secondArg instanceof Arg) {
            return null;
        }

        $tablesExpr = $secondArg->value;

        // Case 1: single string 'sequences' — remove the whole statement.
        if ($tablesExpr instanceof String_ && $tablesExpr->value === 'sequences') {
            return NodeVisitor::REMOVE_NODE;
        }

        // Case 2: array containing 'sequences'.
        if ($tablesExpr instanceof Array_) {
            $newItems = [];
            $foundSequences = false;

            foreach ($tablesExpr->items as $item) {
                if (!$item instanceof ArrayItem) {
                    $newItems[] = $item;
                    continue;
                }
                if ($item->value instanceof String_ && $item->value->value === 'sequences') {
                    $foundSequences = true;
                    // Drop this item.
                    continue;
                }
                $newItems[] = $item;
            }

            if (!$foundSequences) {
                return null;
            }

            // Array is now empty — remove the whole statement.
            if (count($newItems) === 0) {
                return NodeVisitor::REMOVE_NODE;
            }

            // Otherwise update the array without 'sequences'.
            $tablesExpr->items = $newItems;
            return $node;
        }

        return null;
    }
}
