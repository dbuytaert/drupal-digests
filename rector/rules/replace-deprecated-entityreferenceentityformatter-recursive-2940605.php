<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/2940605
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The constant EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT is
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0. Drupal's
// EntityViewBuilder now prevents recursive entity rendering
// automatically via #pre_render and #post_render callbacks in
// getBuildDefaults(), making the old counter-based limit obsolete. This
// rule replaces all class-constant fetches of RECURSIVE_RENDER_LIMIT
// with the literal integer 20 so code remains functional while the
// deprecated reference is removed.
//
// Before:
//   if ($count > EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT) {
//       return [];
//   }
//
// After:
//   if ($count > 20) {
//       return [];
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT
 * with the integer literal 20.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:13.0.0.
 * EntityViewBuilder now prevents recursion via #pre_render / #post_render
 * callbacks and there is no longer an arbitrary counter limit.
 *
 * @see https://www.drupal.org/node/3316878
 */
final class RemoveEntityReferenceRecursiveLimitConstantRector extends AbstractRector
{
    private const DEPRECATED_CLASS = 'Drupal\\Core\\Field\\Plugin\\Field\\FieldFormatter\\EntityReferenceEntityFormatter';
    private const DEPRECATED_CONST = 'RECURSIVE_RENDER_LIMIT';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT with integer 20. The constant is deprecated in drupal:11.4.0 and removed in drupal:13.0.0; EntityViewBuilder now prevents recursive rendering via #pre_render and #post_render callbacks.',
            [
                new CodeSample(
                    'if ($count > EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT) { return []; }',
                    'if ($count > 20) { return []; }'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /** @param ClassConstFetch $node */
    public function refactor(Node $node): ?Node
    {
        // Only target the specific constant name.
        if (!$this->isName($node->name, self::DEPRECATED_CONST)) {
            return null;
        }

        $class = $node->class;

        // Handle EntityReferenceEntityFormatter::RECURSIVE_RENDER_LIMIT
        if ($class instanceof Name) {
            if ($this->isName($class, self::DEPRECATED_CLASS)) {
                return new LNumber(20);
            }
            // static:: or self:: or parent:: usage within a subclass:
            // check if the enclosing class extends EntityReferenceEntityFormatter.
            if (in_array((string) $class, ['static', 'self', 'parent'], true)) {
                if ($this->isObjectType($node, new ObjectType(self::DEPRECATED_CLASS))) {
                    return new LNumber(20);
                }
            }
        }

        return null;
    }
}
