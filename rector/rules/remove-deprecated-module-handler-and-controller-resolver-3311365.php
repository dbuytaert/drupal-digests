<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3311365
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4 deprecated the $module_handler (4th) and
// $controller_resolver (5th) arguments from RouteBuilder::__construct().
// The new 4-argument signature is ($dumper, $lock, $dispatcher,
// $check_provider). This rule detects the old 6-argument new
// RouteBuilder(...) call and drops the two deprecated middle arguments,
// preventing a fatal error in Drupal 12.
//
// Before:
//   new RouteBuilder($dumper, $lock, $dispatcher, $moduleHandler, $controllerResolver, $checkProvider);
//
// After:
//   new RouteBuilder($dumper, $lock, $dispatcher, $checkProvider);


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated $module_handler and $controller_resolver arguments from
 * new RouteBuilder() calls, updating the 6-argument signature to 4 arguments.
 */
final class RemoveRouteBuilderDeprecatedArgsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $module_handler and $controller_resolver arguments from RouteBuilder::__construct()',
            [
                new CodeSample(
                    'new RouteBuilder($dumper, $lock, $dispatcher, $moduleHandler, $controllerResolver, $checkProvider);',
                    'new RouteBuilder($dumper, $lock, $dispatcher, $checkProvider);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->class instanceof Name) {
            return null;
        }

        if (!$this->isName($node->class, 'Drupal\Core\Routing\RouteBuilder')) {
            return null;
        }

        // Old signature: ($dumper, $lock, $dispatcher, $module_handler, $controller_resolver, $check_provider)
        // New signature: ($dumper, $lock, $dispatcher, $check_provider)
        if (count($node->args) !== 6) {
            return null;
        }

        // Keep args 0, 1, 2 and 5; drop 3 and 4.
        $node->args = [
            $node->args[0],
            $node->args[1],
            $node->args[2],
            $node->args[5],
        ];

        return $node;
    }
}
