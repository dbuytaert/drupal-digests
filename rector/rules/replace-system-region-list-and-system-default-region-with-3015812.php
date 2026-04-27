<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3015812
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to the deprecated system_region_list() and
// system_default_region() global functions (deprecated in drupal:11.4.0,
// removed in drupal:13.0.0) to equivalent calls on the Theme extension
// object obtained via \Drupal::service('theme_handler')->getTheme(). The
// second argument of system_region_list() is used to select between
// listVisibleRegions() and listAllRegions().
//
// Before:
//   system_region_list($theme, REGIONS_VISIBLE);
//   system_region_list($theme);
//   system_default_region($theme);
//
// After:
//   \Drupal::service('theme_handler')->getTheme($theme)->listVisibleRegions();
//   \Drupal::service('theme_handler')->getTheme($theme)->listAllRegions();
//   \Drupal::service('theme_handler')->getTheme($theme)->getDefaultRegion();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated system_region_list() and system_default_region() with
 * methods on the Theme object obtained via the theme_handler service.
 */
final class SystemRegionFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated system_region_list() and system_default_region() with Theme object methods via the theme_handler service.',
            [
                new CodeSample(
                    'system_region_list($theme, REGIONS_VISIBLE);',
                    "\\Drupal::service('theme_handler')->getTheme(\$theme)->listVisibleRegions();"
                ),
                new CodeSample(
                    'system_region_list($theme);',
                    "\\Drupal::service('theme_handler')->getTheme(\$theme)->listAllRegions();"
                ),
                new CodeSample(
                    'system_default_region($theme);',
                    "\\Drupal::service('theme_handler')->getTheme(\$theme)->getDefaultRegion();"
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
        $name = $this->getName($node);

        if ($name === 'system_region_list') {
            if (empty($node->args)) {
                return null;
            }
            $themeExpr = $node->args[0]->value;

            // Determine which method to call based on the optional second arg.
            $method = 'listAllRegions';
            if (isset($node->args[1])) {
                $showArg = $node->args[1]->value;
                if (
                    ($showArg instanceof ConstFetch && in_array($this->getName($showArg), ['REGIONS_VISIBLE', 'visible'], true))
                    || ($showArg instanceof String_ && $showArg->value === 'visible')
                ) {
                    $method = 'listVisibleRegions';
                }
            }

            return $this->buildThemeMethodCall($themeExpr, $method);
        }

        if ($name === 'system_default_region') {
            if (empty($node->args)) {
                return null;
            }
            $themeExpr = $node->args[0]->value;
            return $this->buildThemeMethodCall($themeExpr, 'getDefaultRegion');
        }

        return null;
    }

    private function buildThemeMethodCall(Node $themeExpr, string $method): MethodCall
    {
        // Build: \Drupal::service('theme_handler')
        $drupalService = $this->nodeFactory->createStaticCall(
            'Drupal',
            'service',
            [new String_('theme_handler')]
        );

        // Build: ->getTheme($theme)
        $getTheme = $this->nodeFactory->createMethodCall($drupalService, 'getTheme', [$themeExpr]);

        // Build: ->listAllRegions() / ->listVisibleRegions() / ->getDefaultRegion()
        return $this->nodeFactory->createMethodCall($getTheme, $method, []);
    }
}
