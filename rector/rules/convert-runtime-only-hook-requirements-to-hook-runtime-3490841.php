<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3490841
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11 introduced hook_runtime_requirements() to separate runtime
// status-report checks from install/update checks. This rule detects
// procedural hook_requirements($phase) implementations that only handle
// the 'runtime' phase (either via a positive if ($phase == 'runtime')
// block or an early-return guard if ($phase != 'runtime') { return; })
// and renames the function to hook_runtime_requirements(), removing the
// $phase parameter and the phase-guard wrapper. Functions that also
// handle 'install' or 'update' phases are left untouched.
//
// Before:
//   function mymodule_requirements($phase): array {
//     $requirements = [];
//     if ($phase == 'runtime') {
//       $requirements['mymodule'] = [
//         'title' => t('My Module'),
//         'severity' => REQUIREMENT_OK,
//       ];
//     }
//     return $requirements;
//   }
//
// After:
//   function mymodule_runtime_requirements(): array {
//     $requirements = [];
//     $requirements['mymodule'] = [
//       'title' => t('My Module'),
//       'severity' => REQUIREMENT_OK,
//     ];
//     return $requirements;
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Converts hook_requirements($phase) implementations that only handle the
 * 'runtime' phase into hook_runtime_requirements() implementations.
 */
final class ConvertHookRequirementsRuntimeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert hook_requirements($phase) implementations that only handle the runtime phase to hook_runtime_requirements()',
            [
                new CodeSample(
                    'function mymodule_requirements($phase): array {
  $requirements = [];
  if ($phase == \'runtime\') {
    $requirements[\'mymodule\'] = [
      \'title\' => t(\'My Module\'),
      \'severity\' => REQUIREMENT_OK,
    ];
  }
  return $requirements;
}',
                    'function mymodule_runtime_requirements(): array {
  $requirements = [];
  $requirements[\'mymodule\'] = [
    \'title\' => t(\'My Module\'),
    \'severity\' => REQUIREMENT_OK,
  ];
  return $requirements;
}'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Function_::class];
    }

    /** @param Function_ $node */
    public function refactor(Node $node): ?Node
    {
        $name = $this->getName($node);

        // Must end with _requirements but not _runtime_requirements already.
        if ($name === null
            || !str_ends_with($name, '_requirements')
            || str_ends_with($name, '_runtime_requirements')
        ) {
            return null;
        }

        // Must have exactly one parameter named $phase.
        if (count($node->params) !== 1) {
            return null;
        }
        $param = $node->params[0];
        if ($this->getName($param->var) !== 'phase') {
            return null;
        }

        $stmts = $node->stmts ?? [];

        // Reject if any 'install' or 'update' phase string appears in the body.
        if ($this->hasOtherPhaseReference($stmts)) {
            return null;
        }

        // Try Pattern A: if ($phase == 'runtime') { ... } block.
        $patternAResult = $this->tryPatternA($stmts);
        if ($patternAResult !== null) {
            $node->name = new Identifier($this->buildNewName($name));
            $node->params = [];
            $node->stmts = $patternAResult;
            return $node;
        }

        // Try Pattern B: early-return guard if ($phase != 'runtime') { return; }
        $patternBResult = $this->tryPatternB($stmts);
        if ($patternBResult !== null) {
            $node->name = new Identifier($this->buildNewName($name));
            $node->params = [];
            $node->stmts = $patternBResult;
            return $node;
        }

        return null;
    }

    /**
     * Returns true if 'install' or 'update' phase strings appear in the AST.
     *
     * @param Node[] $stmts
     */
    private function hasOtherPhaseReference(array $stmts): bool
    {
        foreach ($stmts as $stmt) {
            if ($this->nodeContainsPhaseString($stmt, ['install', 'update'])) {
                return true;
            }
        }
        return false;
    }

    private function nodeContainsPhaseString(Node $node, array $phases): bool
    {
        if ($node instanceof String_ && in_array($node->value, $phases, true)) {
            return true;
        }
        foreach ($node->getSubNodeNames() as $subName) {
            $sub = $node->$subName;
            if ($sub instanceof Node && $this->nodeContainsPhaseString($sub, $phases)) {
                return true;
            }
            if (is_array($sub)) {
                foreach ($sub as $item) {
                    if ($item instanceof Node && $this->nodeContainsPhaseString($item, $phases)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Pattern A: body is (optional stmts) + if ($phase == 'runtime') { ... } + (optional stmts).
     * Returns new flattened statement list or null if pattern does not match.
     *
     * @param Node\Stmt[] $stmts
     * @return Node\Stmt[]|null
     */
    private function tryPatternA(array $stmts): ?array
    {
        $preStmts = [];
        $ifNode = null;
        $postStmts = [];
        $foundIf = false;

        foreach ($stmts as $stmt) {
            if (!$foundIf) {
                if ($stmt instanceof If_ && $this->isRuntimePhaseCheck($stmt->cond, true)) {
                    if (!empty($stmt->elseifs)) {
                        return null;
                    }
                    if ($stmt->else !== null && !empty($stmt->else->stmts)) {
                        return null;
                    }
                    $ifNode = $stmt;
                    $foundIf = true;
                } elseif ($stmt instanceof If_) {
                    return null;
                } else {
                    $preStmts[] = $stmt;
                }
            } else {
                $postStmts[] = $stmt;
            }
        }

        if ($ifNode === null) {
            return null;
        }

        return array_merge($preStmts, $ifNode->stmts, $postStmts);
    }

    /**
     * Pattern B: first if-statement is an early-return guard.
     * if ($phase != 'runtime') { return ...; }
     *
     * @param Node\Stmt[] $stmts
     * @return Node\Stmt[]|null
     */
    private function tryPatternB(array $stmts): ?array
    {
        if (empty($stmts)) {
            return null;
        }

        $guardIdx = null;
        foreach ($stmts as $idx => $stmt) {
            if ($stmt instanceof If_) {
                $guardIdx = $idx;
                break;
            }
        }

        if ($guardIdx === null) {
            return null;
        }

        $guardIf = $stmts[$guardIdx];
        assert($guardIf instanceof If_);

        if (!$this->isRuntimePhaseCheck($guardIf->cond, false)) {
            return null;
        }

        if (count($guardIf->stmts) !== 1 || !($guardIf->stmts[0] instanceof Return_)) {
            return null;
        }

        if (!empty($guardIf->elseifs) || $guardIf->else !== null) {
            return null;
        }

        $preStmts = array_slice($stmts, 0, $guardIdx);
        $postStmts = array_slice($stmts, $guardIdx + 1);

        return array_merge($preStmts, $postStmts);
    }

    /**
     * Check if a condition is a phase comparison.
     *
     * @param bool $positive True for == 'runtime', false for != 'runtime'.
     */
    private function isRuntimePhaseCheck(Node $cond, bool $positive): bool
    {
        if ($positive) {
            if (!($cond instanceof Equal) && !($cond instanceof Identical)) {
                return false;
            }
        } else {
            if (!($cond instanceof NotEqual) && !($cond instanceof NotIdentical)) {
                return false;
            }
        }

        $left = $cond->left;
        $right = $cond->right;

        if ($right instanceof Variable && $left instanceof String_) {
            [$left, $right] = [$right, $left];
        }

        return $left instanceof Variable
            && $this->getName($left) === 'phase'
            && $right instanceof String_
            && $right->value === 'runtime';
    }

    private function buildNewName(string $name): string
    {
        // mymodule_requirements -> mymodule_runtime_requirements
        return substr($name, 0, -strlen('requirements')) . 'runtime_requirements';
    }
}
