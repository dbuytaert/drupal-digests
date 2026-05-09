<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated PluginBase::isConfigurable() with an
 * equivalent instanceof \Drupal\Component\Plugin\ConfigurableInterface
 * expression. The method was deprecated in Drupal 11.1 and will be
 * removed in 12.0; the instanceof check is semantically identical and
 * works directly on any expression typed as PluginBase or a subclass.
 *
 * Before:
 *   $plugin->isConfigurable();
 *
 * After:
 *   $plugin instanceof \Drupal\Component\Plugin\ConfigurableInterface;
 *
 * Caveats:
 *   Only rewrites calls where the receiver is statically typed as
 *   Drupal\Component\Plugin\PluginBase or a subclass. Calls on untyped
 *   variables or variables typed only as an interface (e.g., a custom
 *   plugin interface that does not extend PluginBase) are skipped;
 *   those should be migrated manually.
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
            'Replace deprecated PluginBase::isConfigurable() with instanceof ConfigurableInterface check.',
            [new CodeSample(
                '$plugin->isConfigurable();',
                '$plugin instanceof \Drupal\Component\Plugin\ConfigurableInterface;',
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
