<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal\Core\Update\UpdateRegistry::__construct() now takes an optional
 * MemoryCacheInterface $memoryCache parameter inserted before the
 * trailing $updateType parameter. Omitting it triggers an
 * E_USER_DEPRECATED notice and will be a hard error in Drupal 12. This
 * rule rewrites direct new UpdateRegistry(...) calls (positional or
 * named) to pass \Drupal::service('cache.memory') in the new slot,
 * correctly shifting an explicitly-passed $updateType.
 *
 * Before:
 *   new UpdateRegistry($root, $sitePath, $module_list, $keyValue, $theme_handler, 'post_update');
 *
 * After:
 *   new UpdateRegistry($root, $sitePath, $module_list, $keyValue, $theme_handler, \Drupal::service('cache.memory'), 'post_update');
 *
 * Caveats:
 *   Only rewrites direct new UpdateRegistry(...) instantiations; code
 *   that obtains the service via
 *   \Drupal::service('update.post_update_registry') or dependency
 *   injection needs no change since core's service definition already
 *   passes the new argument. Calls using ...$args spread/unpacking are
 *   skipped because the argument position cannot be determined
 *   statically; these need manual review.
 *
 * @see https://www.drupal.org/node/3303751
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\VariadicPlaceholder;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddUpdateRegistryMemoryCacheArgumentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the $memoryCache constructor argument to Drupal\Core\Update\UpdateRegistry instantiations.',
            [new CodeSample(
                'new UpdateRegistry($root, $sitePath, $module_list, $keyValue, $theme_handler, \'post_update\');',
                "new UpdateRegistry(\$root, \$sitePath, \$module_list, \$keyValue, \$theme_handler, \\Drupal::service('cache.memory'), 'post_update');",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof New_) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\\Core\\Update\\UpdateRegistry')) {
            return null;
        }

        $args = $node->args;

        // Bail on unpacked/spread args (`...$args`): position cannot be determined safely.
        foreach ($args as $arg) {
            if ($arg instanceof VariadicPlaceholder) {
                return null;
            }
        }

        $memoryCacheCall = new StaticCall(new FullyQualified('Drupal'), 'service', [new Arg(new String_('cache.memory'))]);

        $hasNamedArgs = false;
        foreach ($args as $arg) {
            if ($arg->name !== null) {
                $hasNamedArgs = true;
                if ($this->isName($arg->name, 'memoryCache')) {
                    // Already migrated.
                    return null;
                }
            }
        }

        if ($hasNamedArgs) {
            $node->args[] = new Arg($memoryCacheCall, false, false, [], new Identifier('memoryCache'));
            return $node;
        }

        $argCount = count($args);

        // Fewer than 5 positional args is not a valid call to this constructor
        // ($root, $sitePath, $module_list, $keyValue, $theme_handler are all required); skip.
        if ($argCount < 5) {
            return null;
        }

        // 7+ positional args already include $memoryCache; nothing to do.
        if ($argCount >= 7) {
            return null;
        }

        $memoryCacheArg = new Arg($memoryCacheCall);

        if ($argCount === 5) {
            // No $updateType passed: append $memoryCache as the 6th argument.
            $node->args[] = $memoryCacheArg;
            return $node;
        }

        // $argCount === 6: an explicit $updateType was passed positionally in the old
        // signature's 6th slot. Insert $memoryCache before it, shifting $updateType to 7th.
        array_splice($node->args, 5, 0, [$memoryCacheArg]);
        return $node;
    }
}
