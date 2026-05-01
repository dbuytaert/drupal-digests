<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.4 deprecated the "trusted data" concept in the configuration
 * system. This rule removes the boolean $has_trusted_data argument from
 * Config::save() calls (any boolean literal TRUE/FALSE) and strips the
 * fluent trustData() call from config-entity chains. Both patterns
 * trigger E_USER_DEPRECATED at runtime and are removed in Drupal 13.0.
 *
 * Before:
 *   $config->save(TRUE);
 *   $entity->trustData()->save();
 *
 * After:
 *   $config->save();
 *   $entity->save();
 *
 * Caveats:
 *   Only removes boolean literal arguments (TRUE/FALSE) from save();
 *   variable arguments are left untouched. Standalone ->trustData()
 *   calls not chained to another method call are rewritten to a bare
 *   $entity; expression (harmless dead code) rather than being fully
 *   deleted.
 *
 * @see https://www.drupal.org/node/3347842
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated trusted-data concept from Drupal config.
 *
 * Covers two patterns:
 *  1. $config->save(TRUE) → $config->save()
 *  2. $entity->trustData()->save() → $entity->save()
 */
final class RemoveTrustedDataConceptRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated trusted-data concept: drop boolean arg from Config::save() and remove ConfigEntityBase::trustData() calls.',
            [
                new CodeSample(
                    '$config->save(TRUE);',
                    '$config->save();',
                ),
                new CodeSample(
                    '$entity->trustData()->save();',
                    '$entity->save();',
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
        // Pattern 1: ->save(TRUE) or ->save(FALSE) — remove the deprecated
        // $has_trusted_data argument from Config::save().
        if (
            $this->isName($node->name, 'save')
            && count($node->args) === 1
            && $node->args[0] instanceof \PhpParser\Node\Arg
        ) {
            $argValue = $node->args[0]->value;
            if (
                $argValue instanceof ConstFetch
                && in_array(strtolower($this->getName($argValue->name) ?? ''), ['true', 'false'], true)
            ) {
                $node->args = [];
                return $node;
            }
        }

        // Pattern 2: ->trustData() — remove from a method chain.
        // $entity->trustData()->save() becomes $entity->save() because
        // trustData() returns $this; replacing it with its receiver is safe.
        if ($this->isName($node->name, 'trustData') && count($node->args) === 0) {
            return $node->var;
        }

        return null;
    }
}
