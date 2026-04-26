<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3535439
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The $variables['page'] template variable for taxonomy terms is
// deprecated in Drupal 11.3.0 (removed in 13.0.0). In preprocess hooks,
// reads of $variables['page'] should be replaced with
// $variables['view_mode'] === 'full', which reflects the same condition
// without the deprecated shorthand. Assignment targets are intentionally
// left untouched.
//
// Before:
//   function mymodule_preprocess_taxonomy_term(array &$variables): void {
//     if ($variables['page']) {
//       $variables['show_title'] = FALSE;
//     }
//   }
//
// After:
//   function mymodule_preprocess_taxonomy_term(array &$variables): void {
//     if ($variables['view_mode'] === 'full') {
//       $variables['show_title'] = FALSE;
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitor;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces $variables['page'] reads with $variables['view_mode'] === 'full'
 * in taxonomy term preprocess hooks.
 *
 * The $variables['page'] variable in taxonomy term templates is deprecated
 * in Drupal 11.3.0 and removed in 13.0.0. Use view_mode === 'full' instead.
 *
 * @see https://www.drupal.org/node/3542527
 */
final class TaxonomyTermPageVariableToViewModeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated \$variables['page'] with \$variables['view_mode'] === 'full' in taxonomy term preprocess hooks.",
            [
                new CodeSample(
                    <<<'CODE'
function mymodule_preprocess_taxonomy_term(array &$variables): void {
  if ($variables['page']) {
    $variables['show_title'] = FALSE;
  }
}
CODE,
                    <<<'CODE'
function mymodule_preprocess_taxonomy_term(array &$variables): void {
  if ($variables['view_mode'] === 'full') {
    $variables['show_title'] = FALSE;
  }
}
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Function_::class, ClassMethod::class];
    }

    /**
     * @param Function_|ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        $name = $this->getName($node);
        if ($name === null) {
            return null;
        }

        // Only process taxonomy term preprocess hooks/methods.
        $isPreprocessFunction = str_contains($name, 'preprocess_taxonomy_term')
            || str_contains($name, 'preprocessTaxonomyTerm');

        if (!$isPreprocessFunction) {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable(
            (array) $node->stmts,
            function (Node $subNode) use (&$hasChanged) {
                // If this is an assignment whose LHS is $variables['page'],
                // do NOT descend into it so we don't rewrite the LHS.
                if ($subNode instanceof Assign && $this->isPageVariablesArrayDimFetch($subNode->var)) {
                    return NodeVisitor::DONT_TRAVERSE_CHILDREN;
                }

                if (!$subNode instanceof ArrayDimFetch) {
                    return null;
                }

                if (!$this->isPageVariablesArrayDimFetch($subNode)) {
                    return null;
                }

                $hasChanged = true;

                // Replace with $variables['view_mode'] === 'full'
                return new Identical(
                    new ArrayDimFetch(
                        new Variable('variables'),
                        new String_('view_mode')
                    ),
                    new String_('full')
                );
            }
        );

        if (!$hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * Returns true if the node is $variables['page'].
     */
    private function isPageVariablesArrayDimFetch(Node $node): bool
    {
        if (!$node instanceof ArrayDimFetch) {
            return false;
        }
        if (!$this->isName($node->var, 'variables')) {
            return false;
        }
        return $node->dim instanceof String_ && $node->dim->value === 'page';
    }
}
