<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3347842
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to trustData() on config entities (deprecated in
// drupal:11.4.0, removed in drupal:13.0.0) by stripping the method from
// fluent chains, and removes the deprecated boolean $has_trusted_data
// argument from Config::save() calls. Both were performance hints that
// are no longer needed. Contrib and custom code using these patterns
// triggers E_USER_DEPRECATED errors.
//
// Before:
//   $entity->trustData()->save();
//   $config->save(TRUE);
//
// After:
//   $entity->save();
//   $config->save();


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated trustData() method calls from config entity chains.
 *
 * trustData() is deprecated in drupal:11.4.0 and removed in drupal:13.0.0.
 * It was a no-op optimisation hint; removing it is safe.
 */
final class RemoveTrustDataCallRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated trustData() calls from config entity method chains (deprecated in drupal:11.4.0, removed in drupal:13.0.0)',
            [
                new CodeSample(
                    '$entity->trustData()->save();',
                    '$entity->save();'
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
        if (!$this->isName($node->name, 'trustData')) {
            return null;
        }
        // Replace the whole trustData() call with its receiver, effectively
        // removing it from the chain.
        return $node->var;
    }
}

/**
 * Removes the deprecated $has_trusted_data boolean argument from save() calls.
 *
 * Passing any argument to Config::save() is deprecated in drupal:11.4.0 and
 * removed in drupal:13.0.0. Target boolean literals specifically to avoid
 * false positives on unrelated save() methods.
 */
final class RemoveConfigSaveTrustedDataArgRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated boolean $has_trusted_data argument from Config::save() calls (deprecated in drupal:11.4.0, removed in drupal:13.0.0)',
            [
                new CodeSample(
                    '$config->save(TRUE);',
                    '$config->save();'
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
        if (!$this->isName($node->name, 'save')) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        $arg = $node->args[0];
        if (!$arg instanceof Arg) {
            return null;
        }
        // Only strip boolean literals (TRUE / FALSE) — the deprecated param.
        if (!$arg->value instanceof ConstFetch) {
            return null;
        }
        $constName = strtolower((string) $arg->value->name);
        if ($constName !== 'true' && $constName !== 'false') {
            return null;
        }
        $node->args = [];
        return $node;
    }
}
