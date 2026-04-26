<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3485084
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes defineExtraOptions() method overrides from Views Handler
// subclasses. The method was deprecated in drupal:11.2.0, removed in
// drupal:12.0.0, and has no replacement — Drupal core never called it,
// so any override is dead code. The deprecation trigger in HandlerBase
// itself is left untouched.
//
// Before:
//   use Drupal\views\Plugin\views\HandlerBase;
//   
//   class MyViewsFilter extends HandlerBase {
//     public function defineExtraOptions(&$option) {
//       $option['my_key'] = ['default' => 'value'];
//     }
//   }
//
// After:
//   use Drupal\views\Plugin\views\HandlerBase;
//   
//   class MyViewsFilter extends HandlerBase {
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes overrides of the deprecated HandlerBase::defineExtraOptions() method.
 *
 * The method was deprecated in drupal:11.2.0 and removed in drupal:12.0.0
 * with no replacement. Any override in a subclass is dead code because
 * Drupal core never calls this method.
 *
 * @see https://www.drupal.org/node/3486781
 */
final class RemoveDefineExtraOptionsOverrideRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove overrides of the deprecated HandlerBase::defineExtraOptions() which has no replacement.',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\HandlerBase;

class MyViewsFilter extends HandlerBase {
  public function defineExtraOptions(&$option) {
    $option['my_key'] = ['default' => 'value'];
  }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\HandlerBase;

class MyViewsFilter extends HandlerBase {
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Do not remove from HandlerBase itself — the deprecation trigger stays.
        // Use the short class name (Identifier), not the FQCN from getName().
        if ($node->name instanceof Identifier && $node->name->toString() === 'HandlerBase') {
            return null;
        }

        $changed = false;
        $newStmts = [];

        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $this->getName($stmt) === 'defineExtraOptions') {
                // Drop this method override — no replacement exists.
                $changed = true;
                continue;
            }
            $newStmts[] = $stmt;
        }

        if (!$changed) {
            return null;
        }

        $node->stmts = $newStmts;
        return $node;
    }
}
