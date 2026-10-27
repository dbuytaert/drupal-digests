<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated protected
 * Schema::ensureNotNullPrimaryKey() to validatePrimaryKeySchema() inside
 * classes extending Drupal\Core\Database\Schema (typically contrib
 * database drivers). The new method performs the same NOT NULL check and
 * also flags partial column length primary keys, which are no longer
 * allowed.
 *
 * Before:
 *   $this->ensureNotNullPrimaryKey($table['primary key'], $table['fields']);
 *
 * After:
 *   $this->validatePrimaryKeySchema($table['primary key'], $table['fields']);
 *
 * Caveats:
 *   Skips classes that define their own ensureNotNullPrimaryKey()
 *   override, calls using named or unpacked arguments, and parent::
 *   static calls. Other deprecated behaviour (partial column length in
 *   primary or unique keys) needs manual changes.
 *
 * @see https://www.drupal.org/node/3082239
 * @deprecated drupal:12.1.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EnsureNotNullPrimaryKeyToValidatePrimaryKeySchemaRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema().',
            [new CodeSample(
                '$this->ensureNotNullPrimaryKey($table["primary key"], $table["fields"]);',
                '$this->validatePrimaryKeySchema($table["primary key"], $table["fields"]);',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }
        // A class overriding the deprecated method would change behaviour if redirected to core.
        if ($node->getMethod('ensureNotNullPrimaryKey') !== null) {
            return null;
        }
        $changed = false;
        $this->traverseNodesWithCallable($node->stmts, function (Node $inner) use (&$changed) {
            // Nested (anonymous) classes are visited on their own.
            if ($inner instanceof Class_) {
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }
            if (!$inner instanceof MethodCall) {
                return null;
            }
            if (!$this->isName($inner->name, 'ensureNotNullPrimaryKey')) {
                return null;
            }
            if (!$this->isObjectType($inner->var, new ObjectType('Drupal\\Core\\Database\\Schema'))) {
                return null;
            }
            if (count($inner->args) !== 2) {
                return null;
            }
            foreach ($inner->args as $arg) {
                if (!$arg instanceof Arg || $arg->unpack || $arg->name !== null) {
                    return null;
                }
            }
            $inner->name = new Identifier('validatePrimaryKeySchema');
            $changed = true;
            return $inner;
        });
        return $changed ? $node : null;
    }
}
