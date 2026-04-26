<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571382
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites explicit calls to template_preprocess_layout(&$variables) to
// \Drupal::service(LayoutDiscoveryThemeHooks::class)-
// >preprocessLayout($variables). The procedural function was deprecated
// in drupal:11.3.0 and removed in drupal:12.0.0 (issue #3571382);
// template preprocess functions are now registered directly inside
// hook_theme() and no longer need to be called explicitly.
//
// Before:
//   function my_module_preprocess_layout(array &$variables): void {
//       $variables['my_var'] = 'value';
//       template_preprocess_layout($variables);
//   }
//
// After:
//   function my_module_preprocess_layout(array &$variables): void {
//       $variables['my_var'] = 'value';
//       \Drupal::service(\Drupal\layout_discovery\Hook\LayoutDiscoveryThemeHooks::class)->preprocessLayout($variables);
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the removed template_preprocess_layout() with the LayoutDiscoveryThemeHooks service call.
 *
 * template_preprocess_layout() was deprecated in drupal:11.3.0 and removed in
 * drupal:12.0.0 (issue #3571382). Template preprocess functions are now
 * registered directly in hook_theme(). Any explicit call to the function
 * should be replaced with
 * \Drupal::service(LayoutDiscoveryThemeHooks::class)->preprocessLayout($variables).
 *
 * @see https://www.drupal.org/node/3504125
 * @see https://www.drupal.org/project/drupal/issues/3571382
 */
final class ReplaceTemplatePreprossLayoutRector extends AbstractRector
{
    private const LAYOUT_DISCOVERY_THEME_HOOKS = 'Drupal\\layout_discovery\\Hook\\LayoutDiscoveryThemeHooks';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed template_preprocess_layout() with \\Drupal::service(LayoutDiscoveryThemeHooks::class)->preprocessLayout()',
            [
                new CodeSample(
                    'template_preprocess_layout($variables);',
                    '\\Drupal::service(\\Drupal\\layout_discovery\\Hook\\LayoutDiscoveryThemeHooks::class)->preprocessLayout($variables);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall || !$node->name instanceof Name) {
            return null;
        }

        if ($node->name->toString() !== 'template_preprocess_layout') {
            return null;
        }

        // Build: \Drupal::service(LayoutDiscoveryThemeHooks::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified(self::LAYOUT_DISCOVERY_THEME_HOOKS),
                'class'
            ))]
        );

        // Build: ->preprocessLayout(...original args...)
        return new MethodCall($serviceCall, 'preprocessLayout', $node->args);
    }
}
