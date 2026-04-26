<?php

/**
 * Rector rule: Remove deprecated automated_cron_settings_submit() usage.
 *
 * automated_cron_settings_submit() was deprecated in drupal:11.4.0 and is
 * removed in drupal:13.0.0. Its config-saving logic is now handled
 * automatically by the #config_target property added to the 'interval' element
 * inside hook_form_system_cron_settings_alter(). Any code that called this
 * function directly or registered it as a form #submit handler should remove
 * those references entirely.
 *
 * @see https://www.drupal.org/node/3566774
 */

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566768
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to automated_cron_settings_submit() and string
// references added to $form['#submit'][], both deprecated in
// drupal:11.4.0 and removed in drupal:13.0.0. The config-saving logic is
// now handled automatically by the #config_target property on the
// interval element inside hook_form_system_cron_settings_alter(), so no
// replacement call is needed.
//
// Before:
//   $form['#submit'][] = 'automated_cron_settings_submit';
//   automated_cron_settings_submit($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Rector\AbstractRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\Config\RectorConfig;

/**
 * Removes $form['#submit'][] = 'automated_cron_settings_submit' assignments.
 */
final class RemoveAutomatedCronSettingsSubmitHandlerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Remove deprecated automated_cron_settings_submit form #submit handler assignments (drupal:11.4.0).",
            [
                new CodeSample(
                    '$form[\'#submit\'][] = \'automated_cron_settings_submit\';',
                    '// removed — config is now saved via #config_target on the interval element'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return NodeVisitor::REMOVE_NODE|null
     */
    public function refactor(Node $node): ?int
    {
        $expr = $node->expr;

        // Match: $something['#submit'][] = 'automated_cron_settings_submit'
        if (!$expr instanceof Assign) {
            return null;
        }
        if (!$expr->expr instanceof String_) {
            return null;
        }
        if ($expr->expr->value !== 'automated_cron_settings_submit') {
            return null;
        }
        if (!$expr->var instanceof ArrayDimFetch) {
            return null;
        }
        // Ensure it is an append (no explicit index).
        if ($expr->var->dim !== null) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}

// RemoveFuncCallRector handles: automated_cron_settings_submit($form, $form_state);
// RemoveAutomatedCronSettingsSubmitHandlerRector handles:
//   $form['#submit'][] = 'automated_cron_settings_submit';
