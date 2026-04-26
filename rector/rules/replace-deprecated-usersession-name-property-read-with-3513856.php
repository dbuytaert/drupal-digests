<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3513856
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.3.0, UserSession::$name was made protected and direct
// external read access is deprecated (removed in 12.0.0). This rule
// rewrites $session->name property fetches to
// $session->getAccountName(), which has always been the correct API. It
// skips internal $this->name accesses within the class and dynamic
// property fetches.
//
// Before:
//   $name = $userSession->name;
//
// After:
//   $name = $userSession->getAccountName();


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated direct read access to UserSession::$name with getAccountName().
 */
final class UserSessionNamePropertyToGetAccountNameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated $userSession->name property read with getAccountName()',
            [
                new CodeSample(
                    '$name = $userSession->name;',
                    '$name = $userSession->getAccountName();'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        // Only match statically-known property names (not dynamic $this->$var).
        if (!$node->name instanceof Identifier) {
            return null;
        }

        // Only target the 'name' property.
        if ($this->getName($node->name) !== 'name') {
            return null;
        }

        // Skip $this->name: inside the UserSession class the protected property
        // is accessible directly and its internal use is not deprecated.
        if ($node->var instanceof Variable && $this->getName($node->var) === 'this') {
            return null;
        }

        // Only target UserSession instances (including subclasses like AnonymousUserSession).
        if (!$this->isObjectType($node->var, new ObjectType('Drupal\\Core\\Session\\UserSession'))) {
            return null;
        }

        // Replace $session->name with $session->getAccountName().
        return new MethodCall($node->var, 'getAccountName');
    }
}
