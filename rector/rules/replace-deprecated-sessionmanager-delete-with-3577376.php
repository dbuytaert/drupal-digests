<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3577376
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites SessionManager::delete($uid) calls to \Drupal::service(UserSe
// ssionRepositoryInterface::class)->deleteAll($uid). The method was
// deprecated in drupal:11.4.0 and removed in drupal:12.0.0 (issues
// #3570849 and #3577376); session-deletion responsibility was moved to
// the dedicated UserSessionRepository service. The rule uses PHPStan
// type resolution to only rewrite calls on SessionManager objects,
// avoiding false positives on unrelated delete() methods.
//
// Before:
//   $this->sessionManager->delete($uid);
//
// After:
//   \Drupal::service(\Drupal\Core\Session\UserSessionRepositoryInterface::class)->deleteAll($uid);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated SessionManager::delete() with UserSessionRepositoryInterface::deleteAll().
 *
 * SessionManager::delete($uid) was deprecated in drupal:11.4.0 and removed in
 * drupal:12.0.0 (issues #3570849 and #3577376). The responsibility for removing
 * all sessions of a user was moved to the dedicated UserSessionRepository
 * service. Callers should now use
 * \Drupal::service(UserSessionRepositoryInterface::class)->deleteAll($uid).
 */
final class ReplaceSessionManagerDeleteRector extends AbstractRector
{
    private const SESSION_MANAGER_CLASS = 'Drupal\\Core\\Session\\SessionManager';
    private const USER_SESSION_REPO_INTERFACE = 'Drupal\\Core\\Session\\UserSessionRepositoryInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated SessionManager::delete($uid) with \\Drupal::service(UserSessionRepositoryInterface::class)->deleteAll($uid)',
            [
                new CodeSample(
                    '$this->sessionManager->delete($uid);',
                    '\\Drupal::service(\\Drupal\\Core\\Session\\UserSessionRepositoryInterface::class)->deleteAll($uid);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }

        if (!$this->isName($node->name, 'delete')) {
            return null;
        }

        if (count($node->args) !== 1) {
            return null;
        }

        // Only target calls on SessionManager objects to avoid false positives.
        $callerType = $this->getType($node->var);
        $sessionManagerType = new ObjectType(self::SESSION_MANAGER_CLASS);

        if (!$sessionManagerType->isSuperTypeOf($callerType)->yes()) {
            return null;
        }

        // Build: \Drupal::service(\Drupal\Core\Session\UserSessionRepositoryInterface::class)
        $classConst = new ClassConstFetch(
            new FullyQualified(self::USER_SESSION_REPO_INTERFACE),
            'class'
        );

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConst)]
        );

        // Build: ->deleteAll($uid)
        return new MethodCall(
            $serviceCall,
            'deleteAll',
            [$node->args[0]]
        );
    }
}
