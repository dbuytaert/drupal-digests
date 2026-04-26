<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3520946
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces setConfigurationValue('items_per_page', 'none') with
// setConfigurationValue('items_per_page', NULL) for Views block plugins.
// The string 'none' was deprecated in drupal:11.2.0 and will be removed
// in drupal:12.0.0; NULL is now the canonical way to inherit the default
// items-per-page setting from the view. See
// https://www.drupal.org/node/3522240.
//
// Before:
//   $block->setConfigurationValue('items_per_page', 'none');
//
// After:
//   $block->setConfigurationValue('items_per_page', NULL);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replace setConfigurationValue('items_per_page', 'none') with NULL
 * for Views blocks, per the drupal:11.2.0 deprecation.
 */
final class ViewsBlockItemsPerPageNoneToNullRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace setConfigurationValue(\'items_per_page\', \'none\') with NULL for Views block plugins. The string "none" was deprecated in drupal:11.2.0 and will be removed in drupal:12.0.0; use NULL to inherit the default items-per-page setting from the view.',
            [
                new CodeSample(
                    '$block->setConfigurationValue(\'items_per_page\', \'none\');',
                    '$block->setConfigurationValue(\'items_per_page\', NULL);'
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
        if (!$this->isName($node->name, 'setConfigurationValue')) {
            return null;
        }

        $args = $node->args;
        if (count($args) < 2) {
            return null;
        }

        // First argument must be the string 'items_per_page'.
        $firstArg = $args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }
        if (!$firstArg->value instanceof String_) {
            return null;
        }
        if ($firstArg->value->value !== 'items_per_page') {
            return null;
        }

        // Second argument must be the string 'none'.
        $secondArg = $args[1];
        if (!$secondArg instanceof Arg) {
            return null;
        }
        if (!$secondArg->value instanceof String_) {
            return null;
        }
        if ($secondArg->value->value !== 'none') {
            return null;
        }

        // Replace 'none' with NULL.
        $node->args[1] = new Arg(new Node\Expr\ConstFetch(new Node\Name('NULL')));
        return $node;
    }
}
