<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3536431
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to ModuleHandler::loadAllIncludes(), deprecated in
// Drupal 11.3.0 and removed in 13.0.0, into an equivalent foreach loop
// that calls getModuleList() and loadInclude() for every active module.
// Because the deprecation notice carries no direct API replacement, this
// rule inlines the original implementation so callers continue to work
// without modification.
//
// Before:
//   $this->moduleHandler->loadAllIncludes('install');
//   $this->moduleHandler->loadAllIncludes('inc', 'admin');
//
// After:
//   foreach ($this->moduleHandler->getModuleList() as $module => $filename) {
//       $this->moduleHandler->loadInclude($module, 'install');
//   }
//   foreach ($this->moduleHandler->getModuleList() as $module => $filename) {
//       $this->moduleHandler->loadInclude($module, 'inc', 'admin');
//   }


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated ModuleHandler::loadAllIncludes() with an explicit
 * foreach loop over getModuleList() + loadInclude() calls.
 *
 * @see https://www.drupal.org/project/drupal/issues/3536431
 */
final class LoadAllIncludesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated ModuleHandler::loadAllIncludes() with getModuleList() + loadInclude() loop',
            [
                new CodeSample(
                    '$this->moduleHandler->loadAllIncludes(\'install\');',
                    "foreach (\$this->moduleHandler->getModuleList() as \$module => \$filename) {\n    \$this->moduleHandler->loadInclude(\$module, 'install');\n}"
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
    public function refactor(Node $node): ?Node
    {
        if (!$node->expr instanceof MethodCall) {
            return null;
        }

        $methodCall = $node->expr;

        if (!$this->isName($methodCall->name, 'loadAllIncludes')) {
            return null;
        }

        $caller = $methodCall->var;

        // Build: $caller->getModuleList()
        $getModuleListCall = new MethodCall(clone $caller, 'getModuleList');

        // Loop variables: $module (key), $filename (value)
        $moduleVar   = new Variable('module');
        $filenameVar = new Variable('filename');

        // Build loadInclude($module, ...original args...)
        $loadIncludeArgs = [new Arg($moduleVar)];
        foreach ($methodCall->args as $arg) {
            $loadIncludeArgs[] = $arg;
        }

        $loadIncludeCall = new MethodCall(clone $caller, 'loadInclude', $loadIncludeArgs);

        return new Foreach_(
            $getModuleListCall,
            $filenameVar,
            [
                'keyVar' => $moduleVar,
                'stmts'  => [new Expression($loadIncludeCall)],
            ]
        );
    }
}
