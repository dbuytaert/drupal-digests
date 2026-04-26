<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3432827
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces deprecated addMethodCall('addCachedDiscovery', [new
// Reference($id)]) calls on the plugin.cache_clearer service definition
// with
// $container->getDefinition($id)->addTag('plugin_manager_cache_clear').
// The CachedDiscoveryClearer::addCachedDiscovery() method is deprecated
// in drupal:11.1.0 and removed in drupal:12.0.0; the new approach uses a
// tagged service iterator driven by the plugin_manager_cache_clear tag.
//
// Before:
//   $container->getDefinition('plugin.cache_clearer')
//       ->addMethodCall('addCachedDiscovery', [new Reference('my.plugin.manager')]);
//
// After:
//   $container->getDefinition('my.plugin.manager')->addTag('plugin_manager_cache_clear');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated addMethodCall('addCachedDiscovery', ...) patterns with
 * the new plugin_manager_cache_clear tag approach introduced in Drupal 11.1.
 */
final class ReplaceAddCachedDiscoveryMethodCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated \$def->addMethodCall('addCachedDiscovery', [new Reference(\$id)]) with \$container->getDefinition(\$id)->addTag('plugin_manager_cache_clear')",
            [
                new CodeSample(
                    <<<'CODE'
$container->getDefinition('plugin.cache_clearer')
    ->addMethodCall('addCachedDiscovery', [new Reference('my.plugin.manager')]);
CODE,
                    <<<'CODE'
$container->getDefinition('my.plugin.manager')->addTag('plugin_manager_cache_clear');
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        // Match: ->addMethodCall('addCachedDiscovery', [...])
        if (!$this->isName($node->name, 'addMethodCall')) {
            return null;
        }

        $args = $node->getArgs();
        if (count($args) < 2) {
            return null;
        }

        // First arg must be the string 'addCachedDiscovery'
        $firstArg = $args[0]->value;
        if (!$firstArg instanceof String_ || $firstArg->value !== 'addCachedDiscovery') {
            return null;
        }

        // Second arg must be an array containing a new Reference(...)
        $secondArg = $args[1]->value;
        if (!$secondArg instanceof \PhpParser\Node\Expr\Array_) {
            return null;
        }

        // Find the Reference service ID from the array
        $serviceIdNode = null;
        foreach ($secondArg->items as $item) {
            if ($item === null) {
                continue;
            }
            $itemValue = $item->value;
            if ($itemValue instanceof New_) {
                $className = $itemValue->class;
                if ($className instanceof Name) {
                    $shortName = $className->getLast();
                    if ($shortName === 'Reference' && count($itemValue->getArgs()) >= 1) {
                        $serviceIdNode = $itemValue->getArgs()[0]->value;
                        break;
                    }
                }
            }
        }

        if ($serviceIdNode === null) {
            return null;
        }

        // Build: $container->getDefinition($serviceId)->addTag('plugin_manager_cache_clear')
        $containerVar = new Variable('container');

        $getDefinitionCall = new MethodCall(
            $containerVar,
            'getDefinition',
            [new Arg($serviceIdNode)]
        );

        return new MethodCall(
            $getDefinitionCall,
            'addTag',
            [new Arg(new String_('plugin_manager_cache_clear'))]
        );
    }
}
