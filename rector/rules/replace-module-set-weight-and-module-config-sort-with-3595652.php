<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global functions module_set_weight()
 * and module_config_sort() into equivalent calls on the new
 * Drupal\Core\Extension\ModuleWeight service. Both functions in
 * module.inc were deprecated in Drupal 11.5.0 and will be removed in
 * 13.0.0. Contrib modules commonly call them in hook_install()
 * implementations and test setup, so automated migration saves
 * significant manual effort.
 *
 * Before:
 *   module_set_weight('views', 10);
 *   $sorted = module_config_sort($modules);
 *
 * After:
 *   \Drupal::service(\Drupal\Core\Extension\ModuleWeight::class)->set('views', 10);
 *   $sorted = \Drupal::service(\Drupal\Core\Extension\ModuleWeight::class)->sort($modules);
 *
 * @see https://www.drupal.org/node/3595652
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceModuleIncFunctionsWithModuleWeightRector extends AbstractRector
{
    private const FUNCTION_TO_METHOD = [
        'module_set_weight' => 'set',
        'module_config_sort' => 'sort',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated module_set_weight() and module_config_sort() with \Drupal::service(ModuleWeight::class)->set/sort().',
            [
                new CodeSample(
                    'module_set_weight(\'views\', 10);',
                    '\Drupal::service(\Drupal\Core\Extension\ModuleWeight::class)->set(\'views\', 10);'
                ),
                new CodeSample(
                    '$sorted = module_config_sort($modules);',
                    '$sorted = \Drupal::service(\Drupal\Core\Extension\ModuleWeight::class)->sort($modules);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }

        $method = null;
        foreach (self::FUNCTION_TO_METHOD as $funcName => $methodName) {
            if ($this->isName($node->name, $funcName)) {
                $method = $methodName;
                break;
            }
        }
        if ($method === null) {
            return null;
        }

        $moduleWeightClass = new ClassConstFetch(
            new FullyQualified('Drupal\\Core\\Extension\\ModuleWeight'),
            new Identifier('class')
        );
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            new Identifier('service'),
            [new Arg($moduleWeightClass)]
        );

        return new MethodCall($serviceCall, new Identifier($method), $node->args);
    }
}
