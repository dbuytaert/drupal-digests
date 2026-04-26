<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3459533
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated PluginBase::isConfigurable() method
// with an instanceof \Drupal\Component\Plugin\ConfigurableInterface
// check. The method was deprecated in drupal:11.1.0 and removed in
// drupal:12.0.0. The rule targets only $this->isConfigurable() to avoid
// false positives on other classes (e.g., CKEditor5PluginDefinition,
// Action) that have their own isConfigurable() implementations with
// different semantics.
//
// Before:
//   $this->isConfigurable()
//
// After:
//   $this instanceof \Drupal\Component\Plugin\ConfigurableInterface


use PhpParser\Node;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces calls to the deprecated PluginBase::isConfigurable() with
 * an instanceof check against ConfigurableInterface.
 *
 * Deprecated in drupal:11.1.0, removed in drupal:12.0.0.
 * @see https://www.drupal.org/node/3198285
 */
final class PluginBaseIsConfigurableRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated PluginBase::isConfigurable() calls with instanceof \Drupal\Component\Plugin\ConfigurableInterface',
            [
                new CodeSample(
                    '$this->isConfigurable()',
                    '$this instanceof \Drupal\Component\Plugin\ConfigurableInterface'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Only target calls named isConfigurable with no arguments.
        if ($this->getName($node->name) !== 'isConfigurable') {
            return null;
        }

        if ($node->args !== []) {
            return null;
        }

        // Only rewrite $this->isConfigurable() — the PluginBase pattern.
        // Other classes (CKEditor5PluginDefinition, Action) have their own
        // isConfigurable() with different semantics that must not be rewritten.
        if (!$node->var instanceof Variable) {
            return null;
        }

        if ($this->getName($node->var) !== 'this') {
            return null;
        }

        return new Instanceof_(
            $node->var,
            new FullyQualified('Drupal\Component\Plugin\ConfigurableInterface')
        );
    }
}
