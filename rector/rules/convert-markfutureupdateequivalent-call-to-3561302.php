<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Calling \Drupal::service('update.update_hook_registry')-
 * >markFutureUpdateEquivalent($number, $version) with only the legacy 2
 * arguments inside a hook_update_N() body is deprecated. The replacement
 * is the #[MarkFutureUpdateEquivalent($number, $version)] attribute
 * placed on the update function itself, which also lets the equivalent
 * update register on module install (not just during database updates).
 * This rule moves the two literal arguments from the removed statement
 * onto the enclosing function as an attribute.
 *
 * Before:
 *   function my_module_update_10400(): void {
 *     \Drupal::service('update.update_hook_registry')->markFutureUpdateEquivalent(11101, '11.1.1');
 *   }
 *
 * After:
 *   #[\Drupal\Core\Update\Attribute\MarkFutureUpdateEquivalent(11101, '11.1.1')]
 *   function my_module_update_10400(): void {
 *   }
 *
 * Caveats:
 *   Only rewrites calls with exactly 2 positional literal arguments
 *   (int, string) on \Drupal::service('update.update_hook_registry')-
 *   >markFutureUpdateEquivalent(...), and only when the call is a
 *   direct top-level statement in a function named like
 *   ..._update_<number>. Calls already passing the newer
 *   $module/$ran_update_number arguments, calls using named arguments,
 *   non-literal argument values, or calls inside non-update-hook-named
 *   functions are left untouched to avoid mis-rewrites. The attribute
 *   is emitted with its fully qualified class name rather than adding a
 *   use statement, since automatic import-cleanup in Rector's config
 *   also strips backslashes from unrelated \Drupal::... calls elsewhere
 *   in the same file.
 *
 * @see https://www.drupal.org/node/3561302
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use Rector\Config\RectorConfig;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ConvertMarkFutureUpdateEquivalentCallToAttributeRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Converts a 2-argument UpdateHookRegistry::markFutureUpdateEquivalent() call inside a hook_update_N() body into the #[MarkFutureUpdateEquivalent] attribute on the function.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
function my_module_update_10400(): void {
  \Drupal::service('update.update_hook_registry')->markFutureUpdateEquivalent(11101, '11.1.1');
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[\Drupal\Core\Update\Attribute\MarkFutureUpdateEquivalent(11101, '11.1.1')]
function my_module_update_10400(): void {
}
CODE_SAMPLE
            )],
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
        if (!$node instanceof Function_) {
            return null;
        }
        // The attribute only takes effect on an actual hook_update_N()
        // function: module install reflects on "{module}_update_{schema}".
        if (!preg_match('/_update_\d+$/', $this->getName($node) ?? '')) {
            return null;
        }

        $hasChanged = false;
        foreach ($node->stmts as $key => $stmt) {
            $attribute = $this->matchMarkFutureUpdateEquivalentAttribute($stmt);
            if ($attribute === null) {
                continue;
            }

            $node->attrGroups[] = new AttributeGroup([$attribute]);
            unset($node->stmts[$key]);
            $hasChanged = true;
        }

        if (!$hasChanged) {
            return null;
        }

        $node->stmts = array_values($node->stmts);

        return $node;
    }

    private function matchMarkFutureUpdateEquivalentAttribute(Node $stmt): ?Attribute
    {
        if (!$stmt instanceof Expression) {
            return null;
        }
        $call = $stmt->expr;
        if (!$call instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($call->name, 'markFutureUpdateEquivalent')) {
            return null;
        }
        // Only the deprecated 2-argument call is rewritten. A call already
        // passing $module and $ran_update_number is not the deprecated shape.
        if (count($call->args) !== 2) {
            return null;
        }
        [$numberArg, $versionArg] = $call->args;
        if (!$numberArg instanceof Arg || !$versionArg instanceof Arg) {
            return null;
        }
        // Named arguments could reorder the values; skip rather than risk
        // swapping the number and version when rebuilding as an attribute.
        if ($numberArg->name !== null || $versionArg->name !== null) {
            return null;
        }
        // Attribute arguments must be constant expressions: restrict to the
        // literal shapes actually used in core and contrib.
        if (!$numberArg->value instanceof Int_ || !$versionArg->value instanceof String_) {
            return null;
        }

        $caller = $call->var;
        if (!$caller instanceof StaticCall) {
            return null;
        }
        if (!$this->isName($caller->class, 'Drupal')) {
            return null;
        }
        if (!$this->isName($caller->name, 'service')) {
            return null;
        }
        if (count($caller->args) !== 1 || !$caller->args[0] instanceof Arg) {
            return null;
        }
        if (!$this->valueResolver->isValue($caller->args[0]->value, 'update.update_hook_registry')) {
            return null;
        }

        return new Attribute(
            new FullyQualified('Drupal\Core\Update\Attribute\MarkFutureUpdateEquivalent'),
            [new Arg($numberArg->value), new Arg($versionArg->value)],
        );
    }
}
