<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3039248
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes bare statement calls to views_ui_contextual_links_suppress(),
// views_ui_contextual_links_suppress_push(), and
// views_ui_contextual_links_suppress_pop(), all deprecated in
// drupal:11.4.0 and removed in drupal:12.0.0 with no replacement. These
// functions never worked correctly (the hook they relied on was never
// invoked during preview) and are safe to drop from any call site.
//
// Before:
//   views_ui_contextual_links_suppress_push();
//   // ... preview rendering ...
//   views_ui_contextual_links_suppress_pop();
//
// After:
//   // ... preview rendering ...


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveViewsUiContextualLinksSuppressRector extends AbstractRector
{
    private const DEPRECATED_FUNCTIONS = [
        'views_ui_contextual_links_suppress',
        'views_ui_contextual_links_suppress_push',
        'views_ui_contextual_links_suppress_pop',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to deprecated views_ui_contextual_links_suppress(), views_ui_contextual_links_suppress_push(), and views_ui_contextual_links_suppress_pop() which have no replacement (deprecated in drupal:11.4.0, removed in drupal:12.0.0)',
            [
                new CodeSample(
                    'views_ui_contextual_links_suppress_push();
// ... preview rendering ...
views_ui_contextual_links_suppress_pop();',
                    '// ... preview rendering ...'
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
        if (!$node->expr instanceof FuncCall) {
            return null;
        }

        if (!$this->isNames($node->expr, self::DEPRECATED_FUNCTIONS)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
