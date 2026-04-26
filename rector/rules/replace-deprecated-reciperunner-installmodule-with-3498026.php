<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3498026
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4 deprecated RecipeRunner::installModule() in favour of
// RecipeRunner::installModules(), which accepts an array of module names
// and performs a single bulk install, avoiding redundant router and
// container rebuilds. The rule rewrites every static call by wrapping
// the first argument (a single module name string) in an array literal
// and updating the method name.
//
// Before:
//   RecipeRunner::installModule($module, $recipeConfigStorage, $context);
//
// After:
//   RecipeRunner::installModules([$module], $recipeConfigStorage, $context);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated RecipeRunner::installModule() with installModules().
 */
final class RecipeRunnerInstallModuleRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated RecipeRunner::installModule() with RecipeRunner::installModules(), wrapping the single module name in an array.',
            [
                new CodeSample(
                    'RecipeRunner::installModule($module, $recipeConfigStorage, $context);',
                    'RecipeRunner::installModules([$module], $recipeConfigStorage, $context);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /** @param StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'installModule')) {
            return null;
        }

        // Must be called on RecipeRunner (by name or self/static in subclass).
        if (!($node->class instanceof Name)) {
            return null;
        }

        $className = $this->getName($node->class);
        if ($className !== 'Drupal\\Core\\Recipe\\RecipeRunner'
            && $className !== 'RecipeRunner'
            && $className !== 'static'
            && $className !== 'self'
        ) {
            return null;
        }

        if (empty($node->args)) {
            return null;
        }

        // Wrap the first argument (module name string) in an array literal.
        $firstArg = $node->args[0];
        if (!($firstArg instanceof Arg)) {
            return null;
        }

        $wrappedArray = new Array_([new ArrayItem($firstArg->value)]);
        $newFirstArg = new Arg($wrappedArray);

        $newArgs = array_merge([$newFirstArg], array_slice($node->args, 1));

        $node->name = new Identifier('installModules');
        $node->args = $newArgs;

        return $node;
    }
}
