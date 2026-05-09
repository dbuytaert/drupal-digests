<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In drupal:11.4.0 the RouteBuilder constructor was simplified: the
 * $module_handler and $controller_resolver arguments (positions 3 and 4)
 * were removed, and $check_provider moved from position 5 to position 3.
 * This rule rewrites any new RouteBuilder(...) call that still passes
 * all six arguments to the new four-argument form.
 *
 * Before:
 *   new \Drupal\Core\Routing\RouteBuilder($dumper, $lock, $dispatcher, $moduleHandler, $controllerResolver, $checkProvider);
 *
 * After:
 *   new \Drupal\Core\Routing\RouteBuilder($dumper, $lock, $dispatcher, $checkProvider);
 *
 * Caveats:
 *   Only handles new RouteBuilder(...) direct instantiation. Does not
 *   handle parent::__construct(...) calls inside subclasses of
 *   RouteBuilder, which would require walking up to the enclosing class
 *   to verify the parent chain.
 *
 * @see https://www.drupal.org/node/3311365
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveRouteBuilderDeprecatedArgsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $module_handler and $controller_resolver arguments from RouteBuilder::__construct().',
            [new CodeSample(
                'new \Drupal\Core\Routing\RouteBuilder($dumper, $lock, $dispatcher, $moduleHandler, $controllerResolver, $checkProvider);',
                'new \Drupal\Core\Routing\RouteBuilder($dumper, $lock, $dispatcher, $checkProvider);',
            )],
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
        if (!$node instanceof New_) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\Core\Routing\RouteBuilder')) {
            return null;
        }
        if (count($node->args) !== 6) {
            return null;
        }
        // Remove args at positions 3 ($module_handler) and 4 ($controller_resolver).
        // The 6th arg at position 5 ($check_provider) becomes the new 4th arg.
        $node->args = [
            $node->args[0],
            $node->args[1],
            $node->args[2],
            $node->args[5],
        ];
        return $node;
    }
}
