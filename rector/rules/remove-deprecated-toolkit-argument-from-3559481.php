<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3559481
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The $toolkit constructor argument of ImageToolkitOperationBase was
// deprecated in drupal:11.4.0 and is removed in drupal:13.0.0. The image
// toolkit is now injected via setToolkit() by the
// ImageToolkitOperationManager. This rule removes the
// ImageToolkitInterface $toolkit parameter from subclass constructors
// and drops the corresponding argument from parent::__construct() calls.
//
// Before:
//   public function __construct(
//       array $configuration,
//       string $plugin_id,
//       array $plugin_definition,
//       ImageToolkitInterface $toolkit,
//       LoggerInterface $logger,
//   ) {
//       parent::__construct($configuration, $plugin_id, $plugin_definition, $toolkit, $logger);
//   }
//
// After:
//   public function __construct(
//       array $configuration,
//       string $plugin_id,
//       array $plugin_definition,
//       LoggerInterface $logger,
//   ) {
//       parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated $toolkit argument from ImageToolkitOperationBase
 * subclass constructors and fixes the corresponding parent::__construct() call.
 *
 * The $toolkit argument was deprecated in drupal:11.4.0 and is removed in
 * drupal:13.0.0. The image toolkit is now injected via setToolkit() by the
 * ImageToolkitOperationManager.
 *
 * @see https://www.drupal.org/node/3562304
 */
final class RemoveImageToolkitOperationToolkitArgumentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $toolkit argument from ImageToolkitOperationBase subclass constructors',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
public function __construct(
    array $configuration,
    string $plugin_id,
    array $plugin_definition,
    ImageToolkitInterface $toolkit,
    LoggerInterface $logger,
) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $toolkit, $logger);
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
public function __construct(
    array $configuration,
    string $plugin_id,
    array $plugin_definition,
    LoggerInterface $logger,
) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
}
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Class_);

        // Only process subclasses.
        if ($node->extends === null) {
            return null;
        }

        // Get the constructor method.
        $constructor = $node->getMethod('__construct');
        if ($constructor === null) {
            return null;
        }

        // Find the parameter typed as ImageToolkitInterface.
        $toolkitParamIndex = null;
        $toolkitVarName = null;

        foreach ($constructor->params as $index => $param) {
            if ($param->type === null) {
                continue;
            }
            $typeName = $this->getName($param->type);
            if ($typeName !== null && str_ends_with($typeName, 'ImageToolkitInterface')) {
                $toolkitParamIndex = $index;
                $toolkitVarName = $this->getName($param->var);
                break;
            }
        }

        if ($toolkitParamIndex === null || $toolkitVarName === null) {
            return null;
        }

        // Find parent::__construct() and remove the toolkit argument from it.
        // We only proceed if the toolkit variable is actually passed to
        // parent::__construct(), which confirms the deprecated pattern.
        $parentCallFixed = false;
        $this->traverseNodesWithCallable(
            (array) $constructor->stmts,
            function (Node $subNode) use ($toolkitVarName, &$parentCallFixed) {
                if (!$subNode instanceof StaticCall) {
                    return null;
                }
                if (!$subNode->class instanceof Name || $subNode->class->toString() !== 'parent') {
                    return null;
                }
                if (!$this->isName($subNode->name, '__construct')) {
                    return null;
                }
                foreach ($subNode->args as $argIndex => $arg) {
                    if ($arg->value instanceof Variable
                        && $this->getName($arg->value) === $toolkitVarName
                    ) {
                        array_splice($subNode->args, $argIndex, 1);
                        $parentCallFixed = true;
                        return $subNode;
                    }
                }
                return null;
            }
        );

        // Only proceed if we actually found and fixed the parent call.
        if (!$parentCallFixed) {
            return null;
        }

        // Also remove any $this->toolkit = $toolkit assignments in the
        // constructor body that now reference the removed parameter; leaving
        // them would cause an "undefined variable" PHP error.
        $stmtsToRemove = [];
        foreach ((array) $constructor->stmts as $stmtIndex => $stmt) {
            if (!$stmt instanceof Expression) {
                continue;
            }
            $expr = $stmt->expr;
            if (!$expr instanceof Assign) {
                continue;
            }
            if ($expr->var instanceof PropertyFetch
                && $expr->var->var instanceof Variable
                && $this->getName($expr->var->var) === 'this'
                && $expr->expr instanceof Variable
                && $this->getName($expr->expr) === $toolkitVarName
            ) {
                $stmtsToRemove[] = $stmtIndex;
            }
        }

        foreach (array_reverse($stmtsToRemove) as $removeIndex) {
            array_splice($constructor->stmts, $removeIndex, 1);
        }

        // Remove the toolkit parameter from the constructor signature.
        array_splice($constructor->params, $toolkitParamIndex, 1);

        return $node;
    }
}
