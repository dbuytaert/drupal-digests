<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3442009
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to ModuleHandlerInterface::writeCache() (no replacement
// needed) and replaces calls to ModuleHandlerInterface::getHookInfo()
// with [] — both deprecated in drupal:11.1.0 and removed in
// drupal:12.0.0 as part of the OOP hooks refactor (issue #3442009). The
// new hook system no longer uses a cache or hook-info array, so both
// methods are no-ops.
//
// Before:
//   $this->moduleHandler->writeCache();
//   $hookInfo = $this->moduleHandler->getHookInfo();
//
// After:
//   $hookInfo = [];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes deprecated ModuleHandlerInterface::writeCache() calls and replaces
 * ModuleHandlerInterface::getHookInfo() with [] — both deprecated in
 * drupal:11.1.0 and removed in drupal:12.0.0.
 */
final class RemoveModuleHandlerDeprecatedMethodsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated ModuleHandlerInterface::writeCache() calls (no replacement) and replace ModuleHandlerInterface::getHookInfo() with [] (deprecated in drupal:11.1.0, removed from drupal:12.0.0).',
            [
                new CodeSample(
                    '$this->moduleHandler->writeCache();',
                    ''
                ),
                new CodeSample(
                    '$hookInfo = $this->moduleHandler->getHookInfo();',
                    '$hookInfo = [];'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class, MethodCall::class];
    }

    /**
     * @param Expression|MethodCall $node
     * @return int|Node|null
     */
    public function refactor(Node $node)
    {
        // Remove standalone expression statements for writeCache() and
        // standalone getHookInfo() calls (result not used).
        if ($node instanceof Expression && $node->expr instanceof MethodCall) {
            $call = $node->expr;
            if ($this->isModuleHandlerMethodCall($call, 'writeCache')
                || $this->isModuleHandlerMethodCall($call, 'getHookInfo')) {
                return NodeVisitor::REMOVE_NODE;
            }
        }

        // Replace getHookInfo() used inside a larger expression (e.g. an
        // assignment) with an empty array literal.
        if ($node instanceof MethodCall
            && $this->isModuleHandlerMethodCall($node, 'getHookInfo')) {
            return new Array_([]);
        }

        return null;
    }

    private function isModuleHandlerMethodCall(MethodCall $call, string $methodName): bool
    {
        return $this->isName($call->name, $methodName)
            && $this->isObjectType(
                $call->var,
                new ObjectType('Drupal\Core\Extension\ModuleHandlerInterface')
            );
    }
}
