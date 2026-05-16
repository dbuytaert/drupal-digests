<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites drupal_static_reset('file_get_file_references') and
 * drupal_static_reset('file_get_file_references:field_columns') to
 * \Drupal::service('cache.memory')->invalidateTags(['file_references']).
 * Both static-cache keys were deprecated in Drupal 11.4.0 when the file-
 * reference lookup was moved to the new FileReferenceResolver service,
 * which uses a memory-cache tag instead of drupal_static(). Any contrib
 * or custom code that manually resets these statics needs this update.
 *
 * Before:
 *   drupal_static_reset('file_get_file_references');
 *
 * After:
 *   \Drupal::service('cache.memory')->invalidateTags(['file_references']);
 *
 * Caveats:
 *   Calls to the primary deprecated function file_get_file_references()
 *   are intentionally not rewritten: its replacement
 *   FileReferenceResolver::getReferences() returns a Generator of
 *   FileReferenceUsage objects rather than the nested array the old
 *   function returned, so any surrounding code that uses the return
 *   value requires manual refactoring. Named-argument and unpacked-
 *   argument forms of drupal_static_reset() are also left for manual
 *   review.
 *
 * @see https://www.drupal.org/node/1452100
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceDrupalStaticResetFileReferencesRector extends AbstractRector
{
    // Both static keys were deprecated together (drupal:11.4.0).
    private const DEPRECATED_KEYS = [
        'file_get_file_references',
        'file_get_file_references:field_columns',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace drupal_static_reset() for file_get_file_references keys with cache tag invalidation',
            [new CodeSample(
                "drupal_static_reset('file_get_file_references');",
                "\\Drupal::service('cache.memory')->invalidateTags(['file_references']);",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }
        if (!$this->isName($node->name, 'drupal_static_reset')) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }
        // Skip named and unpacked args — they are exotic enough to leave for
        // manual review.
        if ($firstArg->name !== null || $firstArg->unpack) {
            return null;
        }
        if (!$firstArg->value instanceof String_) {
            return null;
        }
        if (!in_array($firstArg->value->value, self::DEPRECATED_KEYS, true)) {
            return null;
        }

        return new MethodCall(
            new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new String_('cache.memory'))],
            ),
            'invalidateTags',
            [new Arg(new Array_([new ArrayItem(new String_('file_references'))]))],
        );
    }
}
