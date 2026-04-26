<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3518527
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecated storing values directly in $_SESSION (issue
// #3518527). The SessionManager::save() now triggers E_USER_DEPRECATED
// for any key not registered as a session bag. This rule rewrites
// $_SESSION['key'] = $value assignments to
// \Drupal::request()->getSession()->set('key', $value), the documented
// replacement for both string-literal and variable keys.
//
// Before:
//   $_SESSION['my_key'] = $test_value;
//
// After:
//   \Drupal::request()->getSession()->set('my_key', $test_value);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces direct $_SESSION writes with the Symfony session API.
 *
 * Drupal 11.2 deprecated storing values directly in $_SESSION. The replacement
 * is \Drupal::request()->getSession()->set() for writes.
 */
final class SessionSuperGlobalToRequestSessionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated direct $_SESSION writes with \\Drupal::request()->getSession()->set()',
            [
                new CodeSample(
                    '$_SESSION[\'my_key\'] = $value;',
                    '\\Drupal::request()->getSession()->set(\'my_key\', $value);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        // Match: $_SESSION['key'] = $value
        if (!$node->var instanceof ArrayDimFetch) {
            return null;
        }

        $arrayDimFetch = $node->var;

        // The array must be the $_SESSION superglobal.
        if (!$arrayDimFetch->var instanceof Variable) {
            return null;
        }

        if ($this->getName($arrayDimFetch->var) !== '_SESSION') {
            return null;
        }

        // Require an explicit key (not bare $_SESSION[] = ...).
        if ($arrayDimFetch->dim === null) {
            return null;
        }

        // Build: \Drupal::request()
        $drupalRequest = new StaticCall(
            new FullyQualified('Drupal'),
            'request',
            []
        );

        // Build: \Drupal::request()->getSession()
        $getSession = new MethodCall(
            $drupalRequest,
            'getSession',
            []
        );

        // Build: \Drupal::request()->getSession()->set($key, $value)
        return new MethodCall(
            $getSession,
            'set',
            [
                new Arg($arrayDimFetch->dim),
                new Arg($node->expr),
            ]
        );
    }
}
