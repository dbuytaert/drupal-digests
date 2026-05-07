<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global hide() and show() functions by
 * inlining the equivalent #printed property assignment. hide($element)
 * becomes $element['#printed'] = TRUE and show($element) becomes
 * $element['#printed'] = FALSE. Both functions were deprecated in
 * drupal:11.4.0 and will be removed in drupal:13.0.0.
 *
 * Before:
 *   hide($element);
 *
 * After:
 *   $element['#printed'] = TRUE;
 *
 * Caveats:
 *   When hide() or show() return values are used in expression context
 *   (e.g., $x = hide($element)), the rule still rewrites to an
 *   assignment expression, which evaluates to the boolean rather than
 *   the element array. This edge case is rare in practice. For form
 *   elements, the deprecation recommends $element['#access'] =
 *   FALSE/TRUE instead; the rule always emits #printed and cannot
 *   distinguish form elements from render elements at static analysis
 *   time.
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
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class HideShowToInlinePrintedRector extends AbstractRector
{
    // Maps deprecated function name to the #printed boolean value it sets.
    private const FUNCTION_MAP = [
        'hide' => true,
        'show' => false,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated hide() and show() global functions with inline #printed property assignments.',
            [new CodeSample(
                'hide($element);',
                '$element[\'#printed\'] = TRUE;',
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
        $functionName = $this->getName($node->name);
        if ($functionName === null || !array_key_exists($functionName, self::FUNCTION_MAP)) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        $arg = $node->args[0];
        if (!$arg instanceof Arg) {
            return null;
        }
        $printedBool = self::FUNCTION_MAP[$functionName];
        $dimFetch = new ArrayDimFetch($arg->value, new String_('#printed'));
        $boolNode = $printedBool ? $this->nodeFactory->createTrue() : $this->nodeFactory->createFalse();
        return new Assign($dimFetch, $boolNode);
    }
}
