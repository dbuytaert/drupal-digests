<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3346394
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The block_content_add_list theme hook is deprecated in drupal:11.3.0
// and removed in drupal:12.0.0 in favour of entity_add_list. Any
// function implementing hook_preprocess_block_content_add_list (e.g.,
// MYTHEME_preprocess_block_content_add_list) must be renamed to
// MYTHEME_preprocess_entity_add_list. The rule handles .php, .module,
// .theme, and .inc files so contrib modules and themes are all covered.
// See https://www.drupal.org/node/3530643.
//
// Before:
//   function mytheme_preprocess_block_content_add_list(&$variables): void {}
//
// After:
//   function mytheme_preprocess_entity_add_list(&$variables): void {}


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Function_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames hook_preprocess_block_content_add_list implementations to
 * hook_preprocess_entity_add_list, following the deprecation of the
 * block_content_add_list theme hook in drupal:11.3.0.
 */
final class RenameBlockContentAddListPreprocessRector extends AbstractRector
{
    private const OLD_SUFFIX = '_preprocess_block_content_add_list';
    private const NEW_SUFFIX = '_preprocess_entity_add_list';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename hook_preprocess_block_content_add_list() implementations to hook_preprocess_entity_add_list()',
            [
                new CodeSample(
                    'function mytheme_preprocess_block_content_add_list(&$variables): void {}',
                    'function mytheme_preprocess_entity_add_list(&$variables): void {}'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    /** @param Function_ $node */
    public function refactor(Node $node): ?Node
    {
        $name = $this->getName($node);
        if ($name === null) {
            return null;
        }

        if (!str_ends_with($name, self::OLD_SUFFIX)) {
            return null;
        }

        $prefix = substr($name, 0, -strlen(self::OLD_SUFFIX));
        $node->name = new Identifier($prefix . self::NEW_SUFFIX);

        return $node;
    }
}
