<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571063
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to ModuleHandlerInterface::getName($module) (and
// ModuleHandler::getName()) to
// \Drupal::service('extension.list.module')->getName($module). The
// method was deprecated in drupal:10.3.0 and removed in drupal:12.0.0
// (issue #3571063, change record #3310017). The rule uses type-checking
// so it only rewrites calls on objects typed as ModuleHandlerInterface.
//
// Before:
//   $this->moduleHandler->getName($module);
//
// After:
//   \Drupal::service('extension.list.module')->getName($module);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated ModuleHandlerInterface::getName() with extension.list.module service.
 *
 * ModuleHandler::getName($module) was deprecated in drupal:10.3.0 and removed
 * in drupal:12.0.0 (issue #3571063). The replacement is
 * \Drupal::service('extension.list.module')->getName($module).
 *
 * The rule is type-safe: it only rewrites calls on objects typed as
 * ModuleHandlerInterface, so it will not touch unrelated getName() calls.
 *
 * @see https://www.drupal.org/node/3310017
 * @see https://www.drupal.org/project/drupal/issues/3571063
 */
final class ReplaceModuleHandlerGetNameRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated ModuleHandlerInterface::getName() with \\Drupal::service('extension.list.module')->getName()",
            [
                new CodeSample(
                    '$this->moduleHandler->getName($module);',
                    "\\Drupal::service('extension.list.module')->getName(\$module);"
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

        if (!$this->isName($node->name, 'getName')) {
            return null;
        }

        // Only rewrite calls on a ModuleHandlerInterface implementor.
        if (!$this->isObjectType($node->var, new ObjectType('Drupal\\Core\\Extension\\ModuleHandlerInterface'))) {
            return null;
        }

        // Build: \Drupal::service('extension.list.module')
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('extension.list.module'))]
        );

        // Build: ->getName($module)
        return new MethodCall($serviceCall, 'getName', $node->args);
    }
}
