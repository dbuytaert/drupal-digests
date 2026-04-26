<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3522513
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes the deprecated string $root argument from calls to
// Database::convertDbUrlToConnectionInfo(). Since Drupal 11.3,
// Connection::createConnectionOptionsFromUrl() no longer needs the
// Drupal root path, making the parameter obsolete. When a third
// $include_test_drivers argument is present it is shifted to become the
// second argument. Boolean second arguments (the already-adapted
// $include_test_drivers form) and plain variables (whose type cannot be
// resolved statically) are left untouched.
//
// Before:
//   Database::convertDbUrlToConnectionInfo($url, $this->root, TRUE);
//
// After:
//   Database::convertDbUrlToConnectionInfo($url, TRUE);


use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated string $root argument from
 * Database::convertDbUrlToConnectionInfo() calls.
 *
 * In Drupal 11.3 the $root parameter was deprecated because
 * Connection::createConnectionOptionsFromUrl() no longer needs it.
 * See https://www.drupal.org/node/3511287
 */
final class RemoveRootFromConvertDbUrlToConnectionInfoRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated string $root argument from Database::convertDbUrlToConnectionInfo()',
            [
                new CodeSample(
                    'Database::convertDbUrlToConnectionInfo($url, $this->root, TRUE);',
                    'Database::convertDbUrlToConnectionInfo($url, TRUE);'
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
        // Match Database::convertDbUrlToConnectionInfo().
        if (!$this->isName($node->class, 'Drupal\\Core\\Database\\Database')) {
            return null;
        }
        if (!$this->isName($node->name, 'convertDbUrlToConnectionInfo')) {
            return null;
        }

        // Need at least 2 arguments to have a $root argument.
        if (count($node->args) < 2) {
            return null;
        }

        $secondArg = $node->args[1];
        if (!$secondArg instanceof \PhpParser\Node\Arg) {
            return null;
        }
        $secondArgValue = $secondArg->value;

        // ConstFetch: check specific names.
        if ($secondArgValue instanceof ConstFetch) {
            $constName = strtolower($this->getName($secondArgValue));
            // Boolean literals are the non-deprecated $include_test_drivers form.
            if ($constName === 'true' || $constName === 'false') {
                return null;
            }
            // NULL: no root was passed, nothing to remove.
            if ($constName === 'null') {
                return null;
            }
            // Any other constant (e.g. DRUPAL_ROOT) is a string path → deprecated.
        }
        elseif ($secondArgValue instanceof \PhpParser\Node\Expr\Variable) {
            // Plain variables cannot be resolved statically; skip to avoid
            // false positives (the variable may already be a boolean).
            return null;
        }
        // String_ literals, PropertyFetch ($this->root), FuncCall (dirname/realpath),
        // StaticPropertyFetch, etc. are all string values and can be removed safely.
        elseif (
            !$secondArgValue instanceof String_
            && !$secondArgValue instanceof PropertyFetch
            && !$secondArgValue instanceof NullsafePropertyFetch
            && !$secondArgValue instanceof FuncCall
            && !$secondArgValue instanceof \PhpParser\Node\Expr\StaticPropertyFetch
            && !$secondArgValue instanceof \PhpParser\Node\Expr\MethodCall
        ) {
            // Unknown expression type: be conservative and skip.
            return null;
        }

        // Remove the deprecated $root argument (index 1).
        // Any third argument ($include_test_drivers) slides into position 1.
        array_splice($node->args, 1, 1);

        return $node;
    }
}
