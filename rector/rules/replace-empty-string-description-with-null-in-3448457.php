<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3448457
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.2.0, setting the description property of an
// EntityFormMode to an empty string '' was deprecated; it must be null
// in Drupal 12.0.0. This rule targets EntityFormMode::create() calls
// (both short and fully-qualified class names) where description is set
// to '' and replaces the value with NULL. See
// https://www.drupal.org/node/3452144.
//
// Before:
//   EntityFormMode::create([
//     'id' => 'user.test',
//     'label' => 'Test',
//     'description' => '',
//     'targetEntityType' => 'user',
//   ]);
//
// After:
//   EntityFormMode::create([
//     'id' => 'user.test',
//     'label' => 'Test',
//     'description' => NULL,
//     'targetEntityType' => 'user',
//   ]);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces empty-string description with NULL in EntityFormMode::create() calls.
 *
 * In Drupal 11.2.0, setting the description of an EntityFormMode to an empty
 * string was deprecated. Code must use NULL instead.
 *
 * @see https://www.drupal.org/project/drupal/issues/3448457
 * @see https://www.drupal.org/node/3452144
 */
final class EntityFormModeEmptyDescriptionToNullRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace empty-string description with NULL in EntityFormMode::create() calls',
            [
                new CodeSample(
                    "EntityFormMode::create([\n  'id' => 'user.test',\n  'label' => 'Test',\n  'description' => '',\n  'targetEntityType' => 'user',\n]);",
                    "EntityFormMode::create([\n  'id' => 'user.test',\n  'label' => 'Test',\n  'description' => NULL,\n  'targetEntityType' => 'user',\n]);"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Only target create() static calls.
        if (!$this->isName($node->name, 'create')) {
            return null;
        }

        if (!$node->class instanceof Name) {
            return null;
        }

        $className = $this->getName($node->class);
        if ($className !== 'Drupal\\Core\\Entity\\Entity\\EntityFormMode'
            && $className !== 'EntityFormMode'
        ) {
            return null;
        }

        if (empty($node->args)) {
            return null;
        }

        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }

        if (!$firstArg->value instanceof Array_) {
            return null;
        }

        $changed = false;
        foreach ($firstArg->value->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            if ($item->key === null) {
                continue;
            }

            // Check key is the string 'description'.
            if (!$item->key instanceof String_) {
                continue;
            }

            if ($item->key->value !== 'description') {
                continue;
            }

            // Check value is an empty string.
            if (!$item->value instanceof String_) {
                continue;
            }

            if ($item->value->value !== '') {
                continue;
            }

            // Replace '' with NULL.
            $item->value = new ConstFetch(new Name('NULL'));
            $changed = true;
        }

        return $changed ? $node : null;
    }
}
