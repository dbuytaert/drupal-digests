<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes the deprecated $settings['state_cache'] assignment from
 * settings PHP files. This setting was deprecated in drupal:11.0.0
 * because the state cache is now permanently enabled and the setting has
 * no effect. Any assignment of this key should be removed from
 * settings.php and related configuration files.
 *
 * Before:
 *   $settings['state_cache'] = TRUE;
 *
 * Caveats:
 *   Only matches the exact variable name $settings with key state_cache
 *   as a literal string. Assignments via computed keys or different
 *   variable names are not removed.
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
            "Remove deprecated \$settings['state_cache'] assignment from settings files. The state_cache setting is deprecated in drupal:11.0.0 and the setting should be removed.",
            [new CodeSample(
                "\$settings['state_cache'] = TRUE;",
                '',
            )]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): int|null
    {
        if (!$node instanceof Expression) {
            return null;
        }
        $expr = $node->expr;
        if (!$expr instanceof Assign) {
            return null;
        }
        $var = $expr->var;
        if (!$var instanceof ArrayDimFetch) {
            return null;
        }
        if (!$this->isName($var->var, 'settings')) {
            return null;
        }
        if (!$var->dim instanceof String_) {
            return null;
        }
        if ($var->dim->value !== 'state_cache') {
            return null;
        }
        return NodeVisitor::REMOVE_NODE;
    }
}
