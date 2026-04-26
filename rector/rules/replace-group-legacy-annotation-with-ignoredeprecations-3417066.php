<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3417066
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the @group legacy PHPDoc annotation (a symfony/phpunit-bridge
// concept) with the #[\PHPUnit\Framework\Attributes\IgnoreDeprecations]
// PHP 8 attribute required by PHPUnit 10+. Drupal dropped
// symfony/phpunit-bridge in Drupal 11 (issue #3417066); test classes and
// methods that intentionally exercise deprecated APIs must use the
// native PHPUnit attribute instead.
//
// Before:
//   /**
//    * Tests deprecated behaviour.
//    *
//    * @covers ::foo
//    * @group legacy
//    */
//   public function testLegacyBehavior(): void {}
//
// After:
//   /**
//    * Tests deprecated behaviour.
//    *
//    * @covers ::foo
//    */
//   #[\PHPUnit\Framework\Attributes\IgnoreDeprecations]
//   public function testLegacyBehavior(): void {}


use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GroupLegacyToIgnoreDeprecationsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace @group legacy docblock annotation with #[IgnoreDeprecations] PHP 8 attribute for PHPUnit 10+ compatibility',
            [
                new CodeSample(
                    '/**
 * Tests deprecated behaviour.
 *
 * @covers ::foo
 * @group legacy
 */
public function testLegacyBehavior(): void {}',
                    '/**
 * Tests deprecated behaviour.
 *
 * @covers ::foo
 */
#[\PHPUnit\Framework\Attributes\IgnoreDeprecations]
public function testLegacyBehavior(): void {}'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Class_::class];
    }

    /** @param ClassMethod|Class_ $node */
    public function refactor(Node $node): ?Node
    {
        $docComment = $node->getDocComment();
        if ($docComment === null) {
            return null;
        }

        $docText = $docComment->getText();
        if (!str_contains($docText, '@group legacy')) {
            return null;
        }

        // Skip if #[IgnoreDeprecations] attribute is already present.
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $this->getName($attr->name);
                if ($name === 'PHPUnit\\Framework\\Attributes\\IgnoreDeprecations'
                    || $name === 'IgnoreDeprecations'
                ) {
                    return null;
                }
            }
        }

        // Add the #[IgnoreDeprecations] attribute.
        $node->attrGroups[] = new AttributeGroup([
            new Attribute(new FullyQualified('PHPUnit\\Framework\\Attributes\\IgnoreDeprecations')),
        ]);

        // Remove the @group legacy line from the docblock.
        $newDocText = preg_replace('/^[ \t]*\*[ \t]*@group legacy[ \t]*\r?\n/m', '', $docText);

        // Clean up a trailing bare " *\n" left when @group legacy was the last annotation.
        $newDocText = preg_replace('/\n[ \t]*\*[ \t]*\n([ \t]*\*\/)$/', "\n$1", $newDocText);

        // If the docblock is now essentially empty (/** */), remove it entirely.
        if (preg_match('/^\/\*\*\s*\*\/\s*$/', trim($newDocText))) {
            $node->setAttribute('comments', []);
        } else {
            $node->setAttribute('comments', [new Doc($newDocText)]);
        }

        return $node;
    }
}
