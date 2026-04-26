<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3396062
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated NodeStorage::revisionIds() and
// NodeStorage::userRevisionIds() methods (deprecated in Drupal 11.3.0,
// removed in 13.0.0) with equivalent entity queries using array_keys()
// and getQuery(). The revisionIds() replacement queries by nid; the
// userRevisionIds() replacement queries by uid. Only rewrites calls on
// objects typed as NodeStorageInterface.
//
// Before:
//   $ids = $storage->revisionIds($node);
//   $revisions = $storage->userRevisionIds($account);
//
// After:
//   $ids = array_keys($storage->getQuery()->allRevisions()->condition('nid', $node->id())->accessCheck(FALSE)->execute());
//   $revisions = array_keys($storage->getQuery()->allRevisions()->accessCheck(FALSE)->condition('uid', $account->id())->execute());


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated NodeStorage::revisionIds() and
 * NodeStorage::userRevisionIds() with equivalent entity queries.
 *
 * Introduced in Drupal 11.3.0:
 * https://www.drupal.org/node/3519185
 */
final class NodeStorageDeprecatedMethodsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated NodeStorage::revisionIds() and NodeStorage::userRevisionIds() with entity queries.',
            [
                new CodeSample(
                    '$storage->revisionIds($node);',
                    'array_keys($storage->getQuery()->allRevisions()->condition(\'nid\', $node->id())->accessCheck(FALSE)->execute());'
                ),
                new CodeSample(
                    '$storage->userRevisionIds($account);',
                    'array_keys($storage->getQuery()->allRevisions()->accessCheck(FALSE)->condition(\'uid\', $account->id())->execute());'
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
        $methodName = $this->getName($node->name);

        if ($methodName === 'revisionIds') {
            if (!$this->isObjectType($node->var, new ObjectType('Drupal\\node\\NodeStorageInterface'))) {
                return null;
            }
            if (count($node->args) !== 1) {
                return null;
            }

            $nodeArg = $node->args[0] instanceof Arg ? $node->args[0]->value : $node->args[0];

            $getQuery     = $this->nodeFactory->createMethodCall($node->var, 'getQuery');
            $allRevisions = $this->nodeFactory->createMethodCall($getQuery, 'allRevisions');
            $nodeId       = $this->nodeFactory->createMethodCall($nodeArg, 'id');
            $condition    = new MethodCall($allRevisions, 'condition', [
                new Arg(new String_('nid')),
                new Arg($nodeId),
            ]);
            $accessCheck  = new MethodCall($condition, 'accessCheck', [
                new Arg(new ConstFetch(new Name('FALSE'))),
            ]);
            $execute      = $this->nodeFactory->createMethodCall($accessCheck, 'execute');

            return $this->nodeFactory->createFuncCall('array_keys', [new Arg($execute)]);
        }

        if ($methodName === 'userRevisionIds') {
            if (!$this->isObjectType($node->var, new ObjectType('Drupal\\node\\NodeStorageInterface'))) {
                return null;
            }
            if (count($node->args) !== 1) {
                return null;
            }

            $accountArg = $node->args[0] instanceof Arg ? $node->args[0]->value : $node->args[0];

            $getQuery     = $this->nodeFactory->createMethodCall($node->var, 'getQuery');
            $allRevisions = $this->nodeFactory->createMethodCall($getQuery, 'allRevisions');
            $accessCheck  = new MethodCall($allRevisions, 'accessCheck', [
                new Arg(new ConstFetch(new Name('FALSE'))),
            ]);
            $accountId    = $this->nodeFactory->createMethodCall($accountArg, 'id');
            $condition    = new MethodCall($accessCheck, 'condition', [
                new Arg(new String_('uid')),
                new Arg($accountId),
            ]);
            $execute      = $this->nodeFactory->createMethodCall($condition, 'execute');

            return $this->nodeFactory->createFuncCall('array_keys', [new Arg($execute)]);
        }

        return null;
    }
}
