<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal deprecated passing non-boolean, non-AccessResultInterface
 * values to the #access render array key in drupal:11.4.0. This rule
 * targets the most common static case: integer literals. It converts 1
 * (or any non-zero integer) to true and 0 to false, leaving booleans,
 * variables, and AccessResultInterface expressions untouched.
 *
 * Before:
 *   $build = ['#markup' => 'foo', '#access' => 1];
 *
 * After:
 *   $build = ['#markup' => 'foo', '#access' => true];
 *
 * Caveats:
 *   Only handles integer literal values (1 → true, 0 → false).
 *   Variables, string literals, object expressions, and null assigned
 *   to #access are not transformed because the correct boolean
 *   replacement cannot be determined statically without type inference
 *   or runtime context.
 *
 * @see https://www.drupal.org/node/3526250
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceNonBoolAccessRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace integer #access values with proper booleans in render arrays.',
            [new CodeSample(
                "\$build = ['#markup' => 'foo', '#access' => 1];",
                "\$build = ['#markup' => 'foo', '#access' => true];",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ArrayItem::class];
    }

    /** @param ArrayItem $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof ArrayItem) {
            return null;
        }
        if ($node->key === null) {
            return null;
        }
        if (!$node->key instanceof String_) {
            return null;
        }
        if ($node->key->value !== '#access') {
            return null;
        }
        // Only act on integer literals; leave booleans, variables, and
        // AccessResultInterface expressions untouched.
        if (!$node->value instanceof Int_) {
            return null;
        }
        $node->value = $node->value->value === 0
            ? $this->nodeFactory->createFalse()
            : $this->nodeFactory->createTrue();
        return $node;
    }
}
