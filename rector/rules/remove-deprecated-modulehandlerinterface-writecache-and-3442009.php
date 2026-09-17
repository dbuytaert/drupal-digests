<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes calls to ModuleHandlerInterface::writeCache() (deprecated in
 * drupal:11.1.0, does nothing) and replaces calls to
 * ModuleHandlerInterface::getHookInfo() (deprecated in drupal:11.1.0,
 * now always returns []) with an empty array literal. Both methods are
 * removed in drupal:12.0.0 per the OOP hooks initiative (issue
 * #3442009).
 *
 * Before:
 *   $this->moduleHandler->writeCache();
 *   $info = $this->moduleHandler->getHookInfo();
 *
 * After:
 *   $info = [];
 *
 * Caveats:
 *   writeCache() is only removed when called as a standalone expression
 *   statement; calls whose return value is used (unusual since the
 *   method is void) are left untouched. getHookInfo() used as a
 *   standalone statement (discarding the return value) is replaced with
 *   a bare [] expression statement rather than being removed entirely.
 *
 * @see https://www.drupal.org/node/3442009
 * @deprecated drupal:11.1.0
 * @removed drupal:12.0.0
 */


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
 * getHookInfo() calls with an empty array.
 */
final class RemoveModuleHandlerDeprecatedMethodsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to deprecated ModuleHandlerInterface::writeCache() and replace ModuleHandlerInterface::getHookInfo() with []',
            [
                new CodeSample(
                    '$this->moduleHandler->writeCache();',
                    '',
                ),
                new CodeSample(
                    '$info = $this->moduleHandler->getHookInfo();',
                    '$info = [];',
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class, MethodCall::class];
    }

    /** @param Expression|MethodCall $node */
    public function refactor(Node $node): Node|int|null
    {
        $moduleHandlerType = new ObjectType('Drupal\\Core\\Extension\\ModuleHandlerInterface');

        // Remove standalone writeCache() calls.
        if ($node instanceof Expression && $node->expr instanceof MethodCall) {
            $call = $node->expr;
            if (
                $this->isName($call->name, 'writeCache')
                && $this->isObjectType($call->var, $moduleHandlerType)
            ) {
                return NodeVisitor::REMOVE_NODE;
            }
        }

        // Replace getHookInfo() calls with [].
        if (
            $node instanceof MethodCall
            && $this->isName($node->name, 'getHookInfo')
            && $this->isObjectType($node->var, $moduleHandlerType)
        ) {
            return new Array_();
        }

        return null;
    }
}
