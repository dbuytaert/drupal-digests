<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3196937
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.1, passing a string as the $values argument to
// BlockContentTestBase::createBlockContentType() is deprecated and
// removed in Drupal 12.0. The string was treated as both the bundle ID
// and label. Callers must now pass an explicit array such as ['id' =>
// 'basic']. The rule targets only the deprecated signature (second
// argument boolean or absent) to avoid rewriting the unrelated
// InlineBlockTestBase::createBlockContentType($id, $label) variant.
//
// Before:
//   $this->createBlockContentType('basic', TRUE);
//
// After:
//   $this->createBlockContentType(['id' => 'basic'], TRUE);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites deprecated string $values argument in
 * BlockContentTestBase::createBlockContentType() to an array.
 *
 * Drupal 11.1 deprecated passing a string as the first argument.
 * The string was treated as both the bundle ID and label; now callers
 * must pass an explicit array ['id' => '<id>', ...].
 *
 * Only rewrites calls where the second argument is a boolean or absent
 * (matching the signature of the deprecated method) to avoid touching
 * the unrelated InlineBlockTestBase::createBlockContentType($id, $label).
 *
 * @see https://www.drupal.org/node/3473739
 */
final class BlockContentTestBaseStringToArrayRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated string \$values in createBlockContentType() with an ['id' => ...] array",
            [
                new CodeSample(
                    '$this->createBlockContentType(\'basic\', TRUE);',
                    '$this->createBlockContentType([\'id\' => \'basic\'], TRUE);'
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
        if (!$this->isName($node->name, 'createBlockContentType')) {
            return null;
        }

        if (count($node->args) === 0) {
            return null;
        }

        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }

        // Only act when the first argument is a string literal.
        if (!$firstArg->value instanceof String_) {
            return null;
        }

        // Distinguish from InlineBlockTestBase::createBlockContentType($id, $label)
        // which takes two string arguments. The deprecated method's second
        // argument is a boolean ($create_body) or absent.
        if (isset($node->args[1])) {
            $secondArg = $node->args[1];
            if ($secondArg instanceof Arg && $secondArg->value instanceof String_) {
                // Two string arguments — this is the InlineBlockTestBase variant,
                // do not rewrite.
                return null;
            }
        }

        // Replace the string argument with ['id' => <string>].
        $stringNode = $firstArg->value;
        $arrayItem = new ArrayItem($stringNode, new String_('id'));
        $arrayNode = new Array_([$arrayItem]);

        $firstArg->value = $arrayNode;

        return $node;
    }
}
