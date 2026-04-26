<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3575254
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to locale_config_batch_set_config_langcodes($context)
// and locale_config_batch_refresh_name($names, $langcodes, $context) to
// their renamed replacements
// locale_config_batch_update_default_config_langcodes() and
// locale_config_batch_update_config_translations(). Both procedural
// functions from locale.bulk.inc were deprecated in drupal:11.1.0 and
// removed in drupal:12.0.0 (issue #3475054, landed via #3575254).
// Argument order is preserved.
//
// Before:
//   locale_config_batch_set_config_langcodes($context);
//   locale_config_batch_refresh_name($names, $langcodes, $context);
//
// After:
//   locale_config_batch_update_default_config_langcodes($context);
//   locale_config_batch_update_config_translations($names, $langcodes, $context);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces removed locale batch helper functions with their renamed successors.
 *
 * Two procedural functions from locale.bulk.inc were deprecated in
 * drupal:11.1.0 and removed in drupal:12.0.0 (issue #3575254 / #3475054):
 *
 *   locale_config_batch_set_config_langcodes($context)
 *     -> locale_config_batch_update_default_config_langcodes($context)
 *
 *   locale_config_batch_refresh_name($names, $langcodes, $context)
 *     -> locale_config_batch_update_config_translations($names, $langcodes, $context)
 *
 * Both replacements preserve argument order and return-type semantics.
 */
final class ReplaceLocaleConfigBatchFunctionsRector extends AbstractRector
{
    /** Map: removed function name => replacement function name */
    private const FUNC_RENAME_MAP = [
        'locale_config_batch_set_config_langcodes' => 'locale_config_batch_update_default_config_langcodes',
        'locale_config_batch_refresh_name'         => 'locale_config_batch_update_config_translations',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed locale_config_batch_set_config_langcodes() and locale_config_batch_refresh_name() with their renamed successors',
            [
                new CodeSample(
                    'locale_config_batch_set_config_langcodes($context);',
                    'locale_config_batch_update_default_config_langcodes($context);'
                ),
                new CodeSample(
                    'locale_config_batch_refresh_name($names, $langcodes, $context);',
                    'locale_config_batch_update_config_translations($names, $langcodes, $context);'
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
        if (!$node instanceof FuncCall) {
            return null;
        }

        if (!$node->name instanceof Name) {
            return null;
        }

        $funcName = $node->name->toString();

        if (!isset(self::FUNC_RENAME_MAP[$funcName])) {
            return null;
        }

        $node->name = new Name(self::FUNC_RENAME_MAP[$funcName]);
        return $node;
    }
}
