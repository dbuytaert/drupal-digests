<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3526515
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated
// \Drupal\Core\Utility\Error::currentErrorHandler() with the PHP 8.5
// built-in get_error_handler() (polyfilled via symfony/polyfill-php85).
// The static method is deprecated in drupal:11.3.0 and removed in
// drupal:13.0.0. Using the native function is cleaner and avoids the
// overhead of setting and restoring a dummy handler.
//
// Before:
//   $handler = \Drupal\Core\Utility\Error::currentErrorHandler();
//
// After:
//   $handler = get_error_handler();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated Error::currentErrorHandler() with get_error_handler().
 */
final class ErrorCurrentErrorHandlerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated \\Drupal\\Core\\Utility\\Error::currentErrorHandler() with PHP built-in get_error_handler()',
            [
                new CodeSample(
                    '$handler = \\Drupal\\Core\\Utility\\Error::currentErrorHandler();',
                    '$handler = get_error_handler();'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /** @param StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'currentErrorHandler')) {
            return null;
        }

        if (!$this->isObjectType($node->class, new \PHPStan\Type\ObjectType('Drupal\\Core\\Utility\\Error'))) {
            return null;
        }

        return new FuncCall(new Name('get_error_handler'), []);
    }
}
