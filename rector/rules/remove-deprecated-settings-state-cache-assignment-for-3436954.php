<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3436954
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes $settings['state_cache'] assignments from settings files. The
// state_cache setting was deprecated in drupal:11.0.0 — state caching is
// now permanently enabled and the setting has no effect. Leaving it in
// place triggers a deprecation warning via
// Settings::$deprecatedSettings. The replacement is simply removing the
// line.
//
// Before:
//   $settings['state_cache'] = TRUE;


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
            "Remove deprecated \$settings['state_cache'] assignment. The state_cache setting is deprecated in drupal:11.0.0 and should be removed from settings files since state caching is now permanently enabled.",
            [
                new CodeSample(
                    "\$settings['state_cache'] = TRUE;",
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

    /**
     * @param Expression $node
     * @return NodeVisitor::REMOVE_NODE|null
     */
    public function refactor(Node $node): ?int
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
