<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3442810
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Calling Number::alphadecimalToInt() with NULL or an empty string '' is
// deprecated in drupal:11.2.0 and removed in drupal:12.0.0. Both
// arguments always produced a return value of 0, so the rule replaces
// those static calls with the integer literal 0 directly, eliminating
// the deprecation with no behaviour change.
//
// Before:
//   $a = Number::alphadecimalToInt(NULL);
//   $b = Number::alphadecimalToInt('');
//
// After:
//   $a = 0;
//   $b = 0;


use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\LNumber;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replace deprecated Number::alphadecimalToInt(null/'') calls with 0.
 *
 * Passing NULL or an empty string to Number::alphadecimalToInt() is deprecated
 * in drupal:11.2.0 and removed in drupal:12.0.0. Both values always returned 0,
 * so the literal call can be replaced with the integer 0 directly.
 */
final class AlphadecimalToIntNullOrEmptyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Number::alphadecimalToInt(null) or Number::alphadecimalToInt(\'\') calls with 0',
            [
                new CodeSample(
                    'Number::alphadecimalToInt(NULL);',
                    '0;'
                ),
                new CodeSample(
                    "Number::alphadecimalToInt('');",
                    '0;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /** @param StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'alphadecimalToInt')) {
            return null;
        }

        if (!$this->isObjectType($node->class, new \PHPStan\Type\ObjectType('Drupal\\Component\\Utility\\Number'))) {
            return null;
        }

        // Must have exactly one argument.
        if (count($node->args) !== 1) {
            return null;
        }

        $arg = $node->args[0];
        if (!$arg instanceof \PhpParser\Node\Arg) {
            return null;
        }

        $value = $arg->value;

        // Match null literal.
        if ($value instanceof \PhpParser\Node\Expr\ConstFetch) {
            $constName = strtolower($this->getName($value->name));
            if ($constName === 'null') {
                return new LNumber(0);
            }
        }

        // Match empty string literal.
        if ($value instanceof \PhpParser\Node\Scalar\String_ && $value->value === '') {
            return new LNumber(0);
        }

        return null;
    }
}
