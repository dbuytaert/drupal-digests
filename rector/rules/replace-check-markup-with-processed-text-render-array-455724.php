<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated check_markup() function (removed in
 * Drupal 13) with an equivalent ['#type' => 'processed_text', ...]
 * render array. The render array preserves all passed arguments — $text,
 * $format_id, $langcode, and $filter_types_to_skip — mapping them to the
 * corresponding render element keys. Both positional and named argument
 * styles are handled.
 *
 * Before:
 *   check_markup($text, $format_id);
 *
 * After:
 *   ['#type' => 'processed_text', '#text' => $text, '#format' => $format_id];
 *
 * Caveats:
 *   The return type changes from MarkupInterface to a render array. Any
 *   call site that uses the result as a string (concatenation, echo,
 *   cast to string) needs additional manual updates to pass the render
 *   array through a renderer. Call sites that return the render array
 *   directly or nest it inside another render array are fully handled
 *   by this rule.
 *
 * @see https://www.drupal.org/node/455724
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CheckMarkupToProcessedTextRector extends AbstractRector
{
    // Maps check_markup() parameter names to processed_text render array keys.
    private const PARAM_MAP = [
        'text'                 => '#text',
        'format_id'            => '#format',
        'langcode'             => '#langcode',
        'filter_types_to_skip' => '#filter_types_to_skip',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated check_markup() calls with a processed_text render array.',
            [new CodeSample(
                "check_markup(\$text, \$format_id);",
                "['#type' => 'processed_text', '#text' => \$text, '#format' => \$format_id];",
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
        if (!$this->isName($node->name, 'check_markup')) {
            return null;
        }

        $args = $node->args;
        if (count($args) === 0) {
            return null;
        }

        $items = [
            new ArrayItem(new String_('processed_text'), new String_('#type')),
        ];

        $paramNames = array_keys(self::PARAM_MAP);
        $positionalIndex = 0;

        foreach ($args as $arg) {
            // Skip variadic/unpack placeholders.
            if (!$arg instanceof Arg) {
                continue;
            }

            if ($arg->name !== null) {
                // Named argument: map by parameter name.
                $paramName = $arg->name->toString();
                if (isset(self::PARAM_MAP[$paramName])) {
                    $items[] = new ArrayItem($arg->value, new String_(self::PARAM_MAP[$paramName]));
                }
            } else {
                // Positional argument: map by position.
                if (isset($paramNames[$positionalIndex])) {
                    $key = self::PARAM_MAP[$paramNames[$positionalIndex]];
                    $items[] = new ArrayItem($arg->value, new String_($key));
                }
                $positionalIndex++;
            }
        }

        return new Array_($items);
    }
}
