<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated PluginBase::isConfigurable() method
 * with a direct instanceof
 * \Drupal\Component\Plugin\ConfigurableInterface check. The method was
 * deprecated in Drupal 11.1.0 and will be removed in 12.0.0; it was a
 * convenience wrapper around instanceof ConfigurableInterface that is
 * not part of any interface and therefore unsafe to mock in tests.
 *
 * Before:
 *   $plugin->isConfigurable();
 *
 * After:
 *   $plugin instanceof \Drupal\Component\Plugin\ConfigurableInterface;
 *
 * Caveats:
 *   Requires Drupal's autoloader to be available to Rector/PHPStan so
 *   that $this inside a PluginBase subclass is correctly resolved.
 *   Explicitly typed parameters and variables (PluginBase $plugin) are
 *   always transformed.
 *
 * @see https://www.drupal.org/node/3459533
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplacePluginBaseIsConfigurableRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace PluginBase::isConfigurable() with instanceof ConfigurableInterface check.',
            [new CodeSample(
                '$plugin->isConfigurable();',
                '$plugin instanceof \\Drupal\\Component\\Plugin\\ConfigurableInterface;',
            )],
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
        if (!$node instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($node->name, 'isConfigurable')) {
            return null;
        }
        if (!$this->isObjectType($node->var, new ObjectType('Drupal\\Component\\Plugin\\PluginBase'))) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }
        return new Instanceof_(
            $node->var,
            new FullyQualified('Drupal\\Component\\Plugin\\ConfigurableInterface'),
        );
    }
}
