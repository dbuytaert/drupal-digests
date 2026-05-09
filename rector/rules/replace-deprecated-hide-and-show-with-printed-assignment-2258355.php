<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites statement-level calls to the deprecated global hide($element)
 * and show($element) functions to direct $element['#printed'] =
 * true/false property assignment. These functions are deprecated in
 * drupal:11.4.0 and removed in drupal:13.0.0. Only statement-level calls
 * are rewritten; expression-context calls (where the return value is
 * captured) are intentionally skipped because the return type differs.
 *
 * Before:
 *   hide($element);
 *   show($element);
 *
 * After:
 *   $element['#printed'] = true;
 *   $element['#printed'] = false;
 *
 * Caveats:
 *   Only statement-level calls (where the return value is not used) are
 *   rewritten. Expression-context uses such as $result = hide($element)
 *   or if (hide($element)) are skipped because the original returns
 *   $element while the rewrite would evaluate to true, changing
 *   observable behavior. The deprecation also recommends ['#access'] =
 *   FALSE/TRUE for form elements as a more semantically correct
 *   alternative, but this rule applies the mechanical functional
 *   equivalent (#printed) which matches what the deprecated functions
 *   themselves did.
 *
 * @see https://www.drupal.org/node/2258355
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class HideShowFunctionToHashPrintedRector extends AbstractRector
{
    /** @var array<string, bool> */
    private const FUNCTION_TO_VALUE = [
        'hide' => true,
        'show' => false,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated global hide() and show() functions with direct #printed property assignment on the render element.',
            [new CodeSample(
                'hide($element);',
                '$element[\'#printed\'] = TRUE;',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Expression) {
            return null;
        }
        if (!$node->expr instanceof FuncCall) {
            return null;
        }
        $call = $node->expr;
        if (!$this->isNames($call->name, ['hide', 'show'])) {
            return null;
        }
        if (count($call->args) !== 1 || !$call->args[0] instanceof Arg) {
            return null;
        }
        $funcName = $this->getName($call->name);
        if ($funcName === null) {
            return null;
        }
        $value = self::FUNCTION_TO_VALUE[$funcName]
            ? $this->nodeFactory->createTrue()
            : $this->nodeFactory->createFalse();
        $node->expr = new Assign(
            new ArrayDimFetch($call->args[0]->value, new String_('#printed')),
            $value,
        );
        return $node;
    }
}
