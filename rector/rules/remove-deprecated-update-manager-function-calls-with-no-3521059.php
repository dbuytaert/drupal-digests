<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3521059
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes standalone expression-statement calls to five Update Manager
// functions deprecated in drupal:11.2.0 with no replacement:
// update_clear_update_disk_cache(), update_delete_file_if_stale(),
// _update_manager_cache_directory(),
// _update_manager_extract_directory(), and
// _update_manager_unique_identifier(). The disk-based Update Manager
// workflow was removed in favour of Composer. Calls whose return value
// is used must be removed manually.
//
// Before:
//   function mymodule_cron(): void {
//     update_clear_update_disk_cache();
//     update_delete_file_if_stale('/tmp/some-file.tar.gz');
//     _update_manager_cache_directory(FALSE);
//     _update_manager_extract_directory(FALSE);
//     _update_manager_unique_identifier();
//     \Drupal::logger('mymodule')->info('Cron ran.');
//   }
//
// After:
//   function mymodule_cron(): void {
//     \Drupal::logger('mymodule')->info('Cron ran.');
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes standalone expression-statement calls to deprecated Update Manager
 * functions that have no replacement.
 *
 * The functions update_clear_update_disk_cache(), update_delete_file_if_stale(),
 * _update_manager_cache_directory(), _update_manager_extract_directory(), and
 * _update_manager_unique_identifier() were deprecated in drupal:11.2.0 with no
 * replacement (the Update Manager disk install/update workflow was removed in
 * favour of Composer).
 *
 * When any of these functions is used as a standalone expression statement
 * (return value ignored), the statement is deleted automatically. Calls whose
 * return value is actually used must be removed manually.
 */
final class RemoveDeprecatedUpdateManagerFuncCallsRector extends AbstractRector
{
    /**
     * Functions deprecated in drupal:11.2.0 with no replacement.
     *
     * @see https://www.drupal.org/node/3522119
     */
    private const DEPRECATED_FUNCTIONS = [
        'update_clear_update_disk_cache',
        'update_delete_file_if_stale',
        '_update_manager_cache_directory',
        '_update_manager_extract_directory',
        '_update_manager_unique_identifier',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove standalone expression-statement calls to deprecated Update Manager functions (deprecated in drupal:11.2.0, removed in drupal:12.0.0). These functions have no replacement; the disk-based Update Manager workflow was replaced by Composer.',
            [
                new CodeSample(
                    'update_clear_update_disk_cache();',
                    '',
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
     * @return int|null
     */
    public function refactor(Node $node): mixed
    {
        if (!$node->expr instanceof FuncCall) {
            return null;
        }

        $funcCall = $node->expr;
        $name = $this->getName($funcCall);

        if ($name === null) {
            return null;
        }

        if (!in_array(strtolower($name), self::DEPRECATED_FUNCTIONS, true)) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
