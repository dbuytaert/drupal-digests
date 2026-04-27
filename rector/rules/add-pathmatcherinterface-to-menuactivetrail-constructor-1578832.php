<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/1578832
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds the missing PathMatcherInterface $pathMatcher fifth argument to
// MenuActiveTrail::__construct() calls. Omitting it is deprecated in
// drupal:11.2.0 and will be required in drupal:12.0.0 (see
// https://www.drupal.org/node/3523495). Handles both direct new
// MenuActiveTrail(...) instantiation and parent::__construct() calls in
// subclasses.
//
// Before:
//   new MenuActiveTrail($menuLinkManager, $routeMatch, $cache, $lock);
//
// After:
//   new MenuActiveTrail($menuLinkManager, $routeMatch, $cache, $lock, \Drupal::service('path.matcher'));


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MenuActiveTrailPathMatcherRector extends AbstractRector
{
    private const MENU_ACTIVE_TRAIL_CLASS = 'Drupal\Core\Menu\MenuActiveTrail';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add PathMatcherInterface argument to MenuActiveTrail::__construct() calls, deprecated in drupal:11.2.0 and required in drupal:12.0.0.',
            [
                new CodeSample(
                    'new MenuActiveTrail($menuLinkManager, $routeMatch, $cache, $lock);',
                    'new MenuActiveTrail($menuLinkManager, $routeMatch, $cache, $lock, \Drupal::service(\'path.matcher\'));'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class, Class_::class];
    }

    /** @param New_|Class_ $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof New_) {
            return $this->refactorNew($node);
        }
        return $this->refactorClass($node);
    }

    private function refactorNew(New_ $node): ?New_
    {
        if (!$this->isName($node->class, self::MENU_ACTIVE_TRAIL_CLASS)) {
            return null;
        }
        if (count($node->args) !== 4) {
            return null;
        }
        $node->args[] = new Arg(
            $this->nodeFactory->createStaticCall('Drupal', 'service', ['path.matcher'])
        );
        return $node;
    }

    private function refactorClass(Class_ $node): ?Class_
    {
        if ($node->extends === null) {
            return null;
        }
        if (!$this->isName($node->extends, self::MENU_ACTIVE_TRAIL_CLASS)) {
            return null;
        }

        $hasChanges = false;
        $this->traverseNodesWithCallable($node->stmts, function (Node $n) use (&$hasChanges): ?StaticCall {
            if (!($n instanceof StaticCall)) {
                return null;
            }
            if (!$this->isName($n->class, 'parent')) {
                return null;
            }
            if (!$this->isName($n->name, '__construct')) {
                return null;
            }
            if (count($n->args) !== 4) {
                return null;
            }
            $n->args[] = new Arg(
                $this->nodeFactory->createStaticCall('Drupal', 'service', ['path.matcher'])
            );
            $hasChanges = true;
            return $n;
        });

        return $hasChanges ? $node : null;
    }
}
