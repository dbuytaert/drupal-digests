<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In Drupal 11.2.0, the $root parameter of
 * Connection::createConnectionOptionsFromUrl() was deprecated and is
 * unused in all implementations. Passing any non-null value triggers an
 * E_USER_DEPRECATED error. This rule rewrites both method and static
 * calls to pass NULL as the second argument, preparing code for removal
 * in Drupal 12.0.0.
 *
 * Before:
 *   $database = $sqlite_connection->createConnectionOptionsFromUrl($url, $root);
 *
 * After:
 *   $database = $sqlite_connection->createConnectionOptionsFromUrl($url, NULL);
 *
 * @see https://www.drupal.org/node/3506931
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated $root argument in createConnectionOptionsFromUrl()
 * calls with NULL.
 */
final class RemoveRootFromCreateConnectionOptionsFromUrlRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated $root argument in Connection::createConnectionOptionsFromUrl() calls with NULL',
            [
                new CodeSample(
                    '$connection->createConnectionOptionsFromUrl($url, $root);',
                    '$connection->createConnectionOptionsFromUrl($url, NULL);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, StaticCall::class];
    }

    /**
     * @param MethodCall|StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'createConnectionOptionsFromUrl')) {
            return null;
        }

        if ($node instanceof MethodCall) {
            if (!$this->isObjectType($node->var, new ObjectType('Drupal\Core\Database\Connection'))) {
                return null;
            }
        } elseif ($node instanceof StaticCall) {
            if (!$this->isName($node->class, 'Drupal\Core\Database\Connection')) {
                return null;
            }
        }

        // Must have at least two arguments.
        if (count($node->args) < 2) {
            return null;
        }

        $secondArg = $node->args[1];

        // Skip if the argument is already named or unpacked (edge cases).
        if (!$secondArg instanceof Arg) {
            return null;
        }

        // Skip if the second argument is already null.
        $value = $secondArg->value;
        if ($value instanceof ConstFetch && strtolower((string) $value->name) === 'null') {
            return null;
        }

        // Replace the second argument with NULL.
        $node->args[1] = new Arg(new ConstFetch(new Name('NULL')));

        return $node;
    }
}
