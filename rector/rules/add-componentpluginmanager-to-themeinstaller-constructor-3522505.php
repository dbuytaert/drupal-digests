<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3522505
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Detects direct new ThemeInstaller(...) calls that omit the new 13th
// $componentPluginManager argument introduced in drupal:11.2.0. Omitting
// this argument triggers a deprecation notice and will become a fatal
// error in drupal:12.0.0. The rule adds
// \Drupal::service('plugin.manager.sdc') as the missing argument,
// matching the fallback the constructor itself applies internally.
//
// Before:
//   new ThemeInstaller($themeHandler, $configFactory, $configInstaller, $moduleHandler, $configManager, $cssCollectionOptimizer, $routeBuilder, $logger, $state, $moduleExtensionList, $themeRegistry, $themeExtensionList);
//
// After:
//   new ThemeInstaller($themeHandler, $configFactory, $configInstaller, $moduleHandler, $configManager, $cssCollectionOptimizer, $routeBuilder, $logger, $state, $moduleExtensionList, $themeRegistry, $themeExtensionList, \Drupal::service('plugin.manager.sdc'));


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the missing $componentPluginManager argument to new ThemeInstaller() calls.
 *
 * In drupal:11.2.0, ThemeInstaller::__construct() gained a 13th parameter
 * $componentPluginManager (CachedDiscoveryInterface). Omitting it fires a
 * deprecation notice; it will be required in drupal:12.0.0.
 *
 * @see https://www.drupal.org/node/3525649
 */
final class AddComponentPluginManagerToThemeInstallerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the $componentPluginManager argument to new ThemeInstaller() calls that omit it, fixing the drupal:11.2.0 deprecation.',
            [
                new CodeSample(
                    'new ThemeInstaller($themeHandler, $configFactory, $configInstaller, $moduleHandler, $configManager, $cssCollectionOptimizer, $routeBuilder, $logger, $state, $moduleExtensionList, $themeRegistry, $themeExtensionList);',
                    "new ThemeInstaller(\$themeHandler, \$configFactory, \$configInstaller, \$moduleHandler, \$configManager, \$cssCollectionOptimizer, \$routeBuilder, \$logger, \$state, \$moduleExtensionList, \$themeRegistry, \$themeExtensionList, \\Drupal::service('plugin.manager.sdc'));"
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
        if (!($node->class instanceof Name)) {
            return null;
        }

        $className = $this->getName($node->class);
        if ($className !== 'ThemeInstaller' && $className !== 'Drupal\\Core\\Extension\\ThemeInstaller') {
            return null;
        }

        // Only modify calls that are missing the 13th argument.
        if (count($node->args) >= 13) {
            return null;
        }

        // Append \Drupal::service('plugin.manager.sdc') as the 13th argument.
        $drupalClass = new Name\FullyQualified('Drupal');
        $serviceCall = new StaticCall(
            $drupalClass,
            'service',
            [new Arg(new String_('plugin.manager.sdc'))]
        );

        $node->args[] = new Arg($serviceCall);

        return $node;
    }
}
