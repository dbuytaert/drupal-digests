<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566424
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4.0 deprecated Views::pluginManager() and
// Views::handlerManager(). When the argument is a string literal the
// rule emits \Drupal::service('plugin.manager.views.<type>') directly.
// When the argument is a dynamic expression it emits the service-locator
// form \Drupal::service('views.plugin_managers')->get($type). Removal is
// planned for Drupal 13.0.0.
//
// Before:
//   use Drupal\views\Views;
//   
//   $filterManager = Views::handlerManager('filter');
//   $displayManager = Views::pluginManager('display');
//   
//   $type = 'sort';
//   $manager = Views::pluginManager($type);
//
// After:
//   use Drupal\views\Views;
//   
//   $filterManager = \Drupal::service('plugin.manager.views.filter');
//   $displayManager = \Drupal::service('plugin.manager.views.display');
//   
//   $type = 'sort';
//   $manager = \Drupal::service('views.plugin_managers')->get($type);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Rector\Config\RectorConfig;

/**
 * Replaces deprecated Views::pluginManager() and Views::handlerManager() calls.
 *
 * @see https://www.drupal.org/node/3566982
 */
final class ViewsPluginHandlerManagerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Views::pluginManager() and Views::handlerManager() with \\Drupal::service() equivalents.',
            [
                new CodeSample(
                    "Views::handlerManager('filter');\nViews::pluginManager(\$type);",
                    "\\Drupal::service('plugin.manager.views.filter');\n\\Drupal::service('views.plugin_managers')->get(\$type);"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof StaticCall) {
            return null;
        }

        if (!$this->isObjectType($node->class, new \PHPStan\Type\ObjectType('Drupal\\views\\Views'))) {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName !== 'pluginManager' && $methodName !== 'handlerManager') {
            return null;
        }

        if (count($node->args) === 0) {
            return null;
        }

        $arg = $node->args[0];
        if (!$arg instanceof Arg) {
            return null;
        }

        $typeExpr = $arg->value;
        $drupalClass = new \PhpParser\Node\Name\FullyQualified('Drupal');

        if ($typeExpr instanceof String_) {
            // Static type: \Drupal::service('plugin.manager.views.<type>')
            return new StaticCall(
                $drupalClass,
                new Identifier('service'),
                [new Arg(new String_('plugin.manager.views.' . $typeExpr->value))]
            );
        }

        // Dynamic type: \Drupal::service('views.plugin_managers')->get($type)
        $serviceLocatorCall = new StaticCall(
            $drupalClass,
            new Identifier('service'),
            [new Arg(new String_('views.plugin_managers'))]
        );
        return new MethodCall(
            $serviceLocatorCall,
            new Identifier('get'),
            [new Arg($typeExpr)]
        );
    }
}
