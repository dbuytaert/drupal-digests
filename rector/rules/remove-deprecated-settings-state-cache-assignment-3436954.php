<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes assignments to $settings['state_cache'] from settings.php
 * files. The setting was deprecated in Drupal 11.0.0 when state caching
 * was permanently enabled. Any value assigned to this key is now
 * ignored, so the line should simply be deleted.
 *
 * Before:
 *   $settings['state_cache'] = TRUE;
 *
 * Caveats:
 *   Only removes the assignment statement. Any associated doc-block
 *   comment immediately preceding the statement is also removed by
 *   Rector's standard comment handling. Does not warn if
 *   $settings['state_cache'] is read (rather than assigned), since
 *   reads of a deprecated setting key are a no-op in Drupal 11 and
 *   require no action.
 *
 * @see https://www.drupal.org/node/3436954
 * @deprecated drupal:11.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveStateCacheSettingRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Remove the deprecated \$settings['state_cache'] assignment from settings.php files.",
            [new CodeSample(
                "\$settings['state_cache'] = TRUE;",
                '',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    public function refactor(Node $node): mixed
    {
        if (!$node->expr instanceof Assign) {
            return null;
        }

        $assign = $node->expr;

        if (!$assign->var instanceof ArrayDimFetch) {
            return null;
        }

        $arrayDimFetch = $assign->var;

        if (!$this->isName($arrayDimFetch->var, 'settings')) {
            return null;
        }

        if (!$arrayDimFetch->dim instanceof String_) {
            return null;
        }

        if ($arrayDimFetch->dim->value !== 'state_cache') {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
