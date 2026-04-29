<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3184242
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.4.0, the system.performance config keys css.gzip and
// js.gzip were deprecated in favour of css.compress and js.compress, and
// will be removed in Drupal 12.0.0. This rule rewrites
// ->get('css.gzip'), ->get('js.gzip'), ->set('css.gzip', ...), and
// ->set('js.gzip', ...) calls on system.performance config objects to
// use the new key names.
//
// Before:
//   \Drupal::config('system.performance')->get('css.gzip');
//
// After:
//   \Drupal::config('system.performance')->get('css.compress');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated system.performance css.gzip/js.gzip config keys.
 *
 * The config keys system.performance.css.gzip and system.performance.js.gzip
 * were deprecated in Drupal 11.4.0 and will be removed in 12.0.0.
 * Use css.compress and js.compress instead.
 *
 * @see https://www.drupal.org/node/3526344
 */
final class SystemPerformanceGzipToCompressRector extends AbstractRector
{
    /**
     * Config-related method names that accept a config name as their first arg.
     */
    private const CONFIG_ACCESSOR_METHODS = ['config', 'get', 'getEditable'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated system.performance css.gzip/js.gzip config keys with css.compress/js.compress (deprecated in Drupal 11.4.0, removed in 12.0.0)',
            [
                new CodeSample(
                    "\Drupal::config('system.performance')->get('css.gzip');",
                    "\Drupal::config('system.performance')->get('css.compress');"
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
        // Only match get() and set() calls.
        if (!$this->isNames($node->name, ['get', 'set'])) {
            return null;
        }

        if (empty($node->args)) {
            return null;
        }

        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }

        $keyExpr = $firstArg->value;
        if (!$keyExpr instanceof String_) {
            return null;
        }

        $key = $keyExpr->value;
        if ($key !== 'css.gzip' && $key !== 'js.gzip') {
            return null;
        }

        // Require that the receiver chain contains a system.performance config call.
        if (!$this->isSystemPerformanceConfigReceiver($node->var)) {
            return null;
        }

        $newKey = ($key === 'css.gzip') ? 'css.compress' : 'js.compress';
        $node->args[0] = new Arg(new String_($newKey));

        return $node;
    }

    /**
     * Checks whether the expression is (or chains from) a system.performance config object.
     *
     * Matches patterns like:
     *   \Drupal::config('system.performance')
     *   $this->config('system.performance')
     *   \Drupal::configFactory()->get('system.performance')
     *   \Drupal::configFactory()->getEditable('system.performance')
     */
    private function isSystemPerformanceConfigReceiver(Node $receiver): bool
    {
        $current = $receiver;

        while ($current instanceof MethodCall) {
            if ($this->isNames($current->name, self::CONFIG_ACCESSOR_METHODS)) {
                if (!empty($current->args) && $current->args[0] instanceof Arg) {
                    $arg = $current->args[0]->value;
                    if ($arg instanceof String_ && $arg->value === 'system.performance') {
                        return true;
                    }
                }
            }
            $current = $current->var;
        }

        // Also handle \Drupal::config('system.performance') (static call at the root).
        if ($current instanceof StaticCall) {
            if ($this->isName($current->name, 'config') && !empty($current->args)) {
                $arg = $current->args[0];
                if ($arg instanceof Arg && $arg->value instanceof String_) {
                    return $arg->value->value === 'system.performance';
                }
            }
        }

        return false;
    }
}
