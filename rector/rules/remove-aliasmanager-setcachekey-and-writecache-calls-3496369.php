<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3496369
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to AliasManager::setCacheKey() and
// AliasManager::writeCache(), deprecated in drupal:11.3.0 and removed in
// drupal:13.0.0 with no replacement. Both methods became no-ops when the
// path alias preload cache was replaced by a Fiber-based bulk-lookup
// strategy. writeCache() is guarded against false positives with
// ModuleHandler::writeCache() via type resolution.
//
// Before:
//   $this->aliasManager->setCacheKey($path);
//   $this->aliasManager->writeCache();


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes calls to AliasManager::setCacheKey() and AliasManager::writeCache().
 *
 * Both methods are deprecated in drupal:11.3.0 and removed in drupal:13.0.0
 * with no replacement. They are now no-ops and should be deleted from callers.
 */
final class RemoveAliasManagerCacheMethodCallsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to AliasManager::setCacheKey() and AliasManager::writeCache(), deprecated in drupal:11.3.0 and removed in drupal:13.0.0 with no replacement.',
            [
                new CodeSample(
                    '$this->aliasManager->setCacheKey($path);',
                    ''
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     * @return int|null
     */
    public function refactor(Node $node): ?int
    {
        if (!$node->expr instanceof MethodCall) {
            return null;
        }

        $methodCall = $node->expr;
        $methodName = $this->getName($methodCall->name);

        if (!in_array($methodName, ['setCacheKey', 'writeCache'], true)) {
            return null;
        }

        // For writeCache(), guard against false positives (e.g. ModuleHandler).
        // Accept only when the receiver is typed as AliasManager or
        // AliasManagerInterface.
        if ($methodName === 'writeCache') {
            $aliasManagerType = new ObjectType('Drupal\\path_alias\\AliasManager');
            $aliasManagerInterfaceType = new ObjectType('Drupal\\path_alias\\AliasManagerInterface');
            if (
                !$this->isObjectType($methodCall->var, $aliasManagerType) &&
                !$this->isObjectType($methodCall->var, $aliasManagerInterfaceType)
            ) {
                return null;
            }
        }

        // Remove the expression statement entirely.
        return NodeVisitor::REMOVE_NODE;
    }
}
