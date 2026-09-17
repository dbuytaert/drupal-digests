<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the global functions
 * user_role_grant_permissions(), user_role_revoke_permissions(), and
 * user_role_change_permissions() in favor of the grantPermissions(),
 * revokePermissions(), and changePermissions() methods on RoleInterface.
 * This rule rewrites each FuncCall into \Drupal\user\Entity\Role::loadOv
 * errideFree($rid)?->method($permissions)?->save(), using nullsafe
 * chaining so behavior stays safe when the role does not exist, matching
 * the pattern Drupal core itself uses in media.install and node.install.
 *
 * Before:
 *   user_role_grant_permissions('anonymous', ['access content']);
 *   user_role_revoke_permissions('anonymous', ['access content']);
 *   user_role_change_permissions('anonymous', ['access content' => TRUE]);
 *
 * After:
 *   \Drupal\user\Entity\Role::loadOverrideFree('anonymous')?->grantPermissions(['access content'])?->save();
 *   \Drupal\user\Entity\Role::loadOverrideFree('anonymous')?->revokePermissions(['access content'])?->save();
 *   \Drupal\user\Entity\Role::loadOverrideFree('anonymous')?->changePermissions(['access content' => TRUE])?->save();
 *
 * Caveats:
 *   Skips calls using named arguments or argument unpacking (...$args),
 *   and calls with more than two positional arguments (the deprecated
 *   functions never had a third). user_role_revoke_permissions() did
 *   not null-check the loaded role internally (it would fatal on an
 *   unknown $rid), while the rewritten nullsafe chain silently no-ops
 *   instead; this is strictly safer and matches the pattern core itself
 *   adopted for the other two functions.
 *
 * @see https://www.drupal.org/node/2025089
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUserRolePermissionFunctionsRector extends AbstractRector
{
    /** @var array<string, string> */
    private const FUNCTION_TO_METHOD = [
        'user_role_grant_permissions' => 'grantPermissions',
        'user_role_revoke_permissions' => 'revokePermissions',
        'user_role_change_permissions' => 'changePermissions',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace user_role_grant_permissions(), user_role_revoke_permissions() and user_role_change_permissions() with the corresponding RoleInterface method, loading the role with Role::loadOverrideFree() first.',
            [new CodeSample(
                "user_role_grant_permissions('anonymous', ['access content']);",
                "\\Drupal\\user\\Entity\\Role::loadOverrideFree('anonymous')?->grantPermissions(['access content'])?->save();",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }

        $functionName = $this->getName($node->name);
        if ($functionName === null || !isset(self::FUNCTION_TO_METHOD[$functionName])) {
            return null;
        }

        if (count($node->args) < 1 || count($node->args) > 2) {
            return null;
        }

        // Skip named arguments and argument unpacking; too rare to be worth the complexity.
        foreach ($node->args as $arg) {
            if (!$arg instanceof Arg) {
                return null;
            }
            if ($arg->name !== null || $arg->unpack) {
                return null;
            }
        }

        $ridArg = $node->args[0];
        $permissionsArg = $node->args[1] ?? new Arg(new Array_([]));

        $methodName = self::FUNCTION_TO_METHOD[$functionName];

        $loadCall = new StaticCall(
            new FullyQualified('Drupal\\user\\Entity\\Role'),
            'loadOverrideFree',
            [$ridArg],
        );

        $permissionsCall = new NullsafeMethodCall($loadCall, $methodName, [$permissionsArg]);

        return new NullsafeMethodCall($permissionsCall, 'save');
    }
}
