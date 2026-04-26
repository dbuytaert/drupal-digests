<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3550268
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal's testing framework removed the expectDeprecation(),
// expectDeprecationMessage(), and expectDeprecationMessageMatches()
// methods (issue #3550268). The bare $this->expectDeprecation() call is
// removed entirely, while expectDeprecationMessage($msg) is renamed to
// PHPUnit 11's native expectUserDeprecationMessage($msg) and
// expectDeprecationMessageMatches($pattern) to
// expectUserDeprecationMessageMatches($pattern).
//
// Before:
//   $this->expectDeprecation();
//   $this->expectDeprecationMessage('Foo::bar is deprecated in mymodule:2.0.0');
//
// After:
//   $this->expectUserDeprecationMessage('Foo::bar is deprecated in mymodule:2.0.0');


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Rector\Config\RectorConfig;

/**
 * Replaces Drupal's removed expectDeprecation*() methods with PHPUnit 11+
 * expectUserDeprecationMessage*() equivalents.
 *
 * - Removes bare $this->expectDeprecation() calls (no arguments).
 * - Renames $this->expectDeprecationMessage($msg) to
 *   $this->expectUserDeprecationMessage($msg).
 * - Renames $this->expectDeprecationMessageMatches($pattern) to
 *   $this->expectUserDeprecationMessageMatches($pattern).
 */
final class ReplaceExpectDeprecationRector extends AbstractRector
{
    private const RENAME_MAP = [
        'expectDeprecationMessage' => 'expectUserDeprecationMessage',
        'expectDeprecationMessageMatches' => 'expectUserDeprecationMessageMatches',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace Drupal\'s removed expectDeprecation*() methods with PHPUnit 11+ expectUserDeprecationMessage*() equivalents',
            [
                new CodeSample(
                    '$this->expectDeprecation();
$this->expectDeprecationMessage(\'Foo is deprecated\');',
                    '$this->expectUserDeprecationMessage(\'Foo is deprecated\');'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): Node|int|null
    {
        $expr = $node->expr;
        if (!$expr instanceof MethodCall) {
            return null;
        }

        // Only handle $this->... calls.
        if (!$expr->var instanceof Variable || $expr->var->name !== 'this') {
            return null;
        }

        $methodName = $this->getName($expr->name);

        // Remove bare $this->expectDeprecation() (no arguments).
        if ($methodName === 'expectDeprecation' && $expr->args === []) {
            return NodeVisitor::REMOVE_NODE;
        }

        // Rename expectDeprecationMessage / expectDeprecationMessageMatches.
        if (isset(self::RENAME_MAP[$methodName])) {
            $expr->name = new Identifier(self::RENAME_MAP[$methodName]);
            return $node;
        }

        return null;
    }
}
