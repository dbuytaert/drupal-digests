<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3525077
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11 deprecated passing PDO::FETCH_* integer constants as the
// fetch query option and as arguments to statement methods such as
// setFetchMode(), fetch(), fetchAll(), and fetchAllAssoc(). The
// replacement is the \Drupal\Core\Database\Statement\FetchAs enum, which
// provides named cases (FetchAs::Object, FetchAs::Associative,
// FetchAs::List, FetchAs::Column, FetchAs::ClassObject). Removed in
// Drupal 12.
//
// Before:
//   $result = $db->query('SELECT name FROM {test}', [], ['fetch' => \PDO::FETCH_ASSOC]);
//   $statement->setFetchMode(\PDO::FETCH_OBJ);
//   $rows = $statement->fetchAll(\PDO::FETCH_NUM);
//   $data = $statement->fetchAllAssoc('id', \PDO::FETCH_ASSOC);
//
// After:
//   use Drupal\Core\Database\Statement\FetchAs;
//   
//   $result = $db->query('SELECT name FROM {test}', [], ['fetch' => FetchAs::Associative]);
//   $statement->setFetchMode(FetchAs::Object);
//   $rows = $statement->fetchAll(FetchAs::List);
//   $data = $statement->fetchAllAssoc('id', FetchAs::Associative);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts PDO::FETCH_* constants to FetchAs enum cases in Drupal DB API.
 *
 * Drupal 11 deprecated passing PDO::FETCH_* integer constants to Database
 * statement fetch methods and the 'fetch' query option. The replacement uses
 * the \Drupal\Core\Database\Statement\FetchAs enum.
 *
 * @see https://www.drupal.org/node/3488338
 */
final class PdoFetchConstToFetchAsRector extends AbstractRector
{
    /**
     * Map of PDO::FETCH_* constant names to FetchAs case names.
     */
    private const FETCH_MAP = [
        'FETCH_OBJ'    => 'Object',
        'FETCH_ASSOC'  => 'Associative',
        'FETCH_NUM'    => 'List',
        'FETCH_COLUMN' => 'Column',
        'FETCH_CLASS'  => 'ClassObject',
    ];

    /**
     * Methods on Drupal statement objects that accept a FetchAs mode.
     *
     * Key is method name, value is the argument index of the fetch mode.
     */
    private const DRUPAL_FETCH_METHODS = [
        'setFetchMode'  => 0,
        'fetch'         => 0,
        'fetchAll'      => 0,
        'fetchAllAssoc' => 1,
    ];

    /**
     * Method names whose return value is a raw PDO object.
     *
     * Calls chained on these must NOT be rewritten.
     */
    private const PDO_RETURN_METHODS = [
        'getClientStatement',
        'getClientConnection',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace PDO::FETCH_* constants with FetchAs enum cases in Drupal Database API calls',
            [
                new CodeSample(
                    '$statement->setFetchMode(\PDO::FETCH_ASSOC);',
                    '$statement->setFetchMode(\Drupal\Core\Database\Statement\FetchAs::Associative);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, ArrayItem::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }
        if ($node instanceof ArrayItem) {
            return $this->refactorArrayItem($node);
        }
        return null;
    }

    private function refactorMethodCall(MethodCall $node): ?MethodCall
    {
        $methodName = $this->getName($node->name);
        if ($methodName === null || !array_key_exists($methodName, self::DRUPAL_FETCH_METHODS)) {
            return null;
        }

        // Skip calls where the object is the result of getClientStatement() /
        // getClientConnection() — those are raw PDO objects that legitimately
        // accept PDO::FETCH_* integers.
        if ($node->var instanceof MethodCall) {
            $calleeName = $this->getName($node->var->name);
            if ($calleeName !== null && in_array($calleeName, self::PDO_RETURN_METHODS, true)) {
                return null;
            }
        }

        $fetchArgIndex = self::DRUPAL_FETCH_METHODS[$methodName];
        $changed = false;

        foreach ($node->args as $index => $arg) {
            if (!$arg instanceof Arg || $index !== $fetchArgIndex) {
                continue;
            }

            $replacement = $this->replacePdoFetchConst($arg->value);
            if ($replacement !== null) {
                $arg->value = $replacement;
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    private function refactorArrayItem(ArrayItem $node): ?ArrayItem
    {
        if ($node->key === null) {
            return null;
        }
        // Only the 'fetch' key in query-options arrays.
        if (!($node->key instanceof String_) || $node->key->value !== 'fetch') {
            return null;
        }

        $replacement = $this->replacePdoFetchConst($node->value);
        if ($replacement === null) {
            return null;
        }

        $node->value = $replacement;
        return $node;
    }

    private function replacePdoFetchConst(Node $node): ?ClassConstFetch
    {
        if (!$node instanceof ClassConstFetch) {
            return null;
        }

        $className = $this->getName($node->class);
        if ($className !== 'PDO') {
            return null;
        }

        $constName = $this->getName($node->name);
        if ($constName === null || !isset(self::FETCH_MAP[$constName])) {
            return null;
        }

        return new ClassConstFetch(
            new FullyQualified('Drupal\Core\Database\Statement\FetchAs'),
            self::FETCH_MAP[$constName]
        );
    }
}
