<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3528899
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes calls to ModuleHandlerInterface::addModule() and addProfile(),
// which were deprecated in drupal:11.2.0, are no-ops since then, and
// will be removed in drupal:12.0.0. Because these methods do nothing
// (and never had a replacement), the correct fix is simply to delete the
// call sites. The rule uses type information to avoid touching
// addModule() or addProfile() methods on unrelated classes.
//
// Before:
//   $moduleHandler->addModule('mymodule', 'modules/mymodule');
//   $moduleHandler->addProfile('standard', 'core/profiles/standard');


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveModuleHandlerAddModuleCallsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated ModuleHandlerInterface::addModule() and addProfile() calls, which are no-ops since drupal:11.2.0 and removed in drupal:12.0.0.',
            [
                new CodeSample(
                    '$moduleHandler->addModule(\'mymodule\', \'modules/mymodule\');',
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

    /** @param Expression $node */
    public function refactor(Node $node): ?int
    {
        if (!($node->expr instanceof MethodCall)) {
            return null;
        }
        $methodCall = $node->expr;
        if (!$this->isNames($methodCall->name, ['addModule', 'addProfile'])) {
            return null;
        }
        if ($this->isObjectType($methodCall->var, new ObjectType('Drupal\\Core\\Extension\\ModuleHandlerInterface'))) {
            return NodeVisitor::REMOVE_NODE;
        }
        return null;
    }
}
