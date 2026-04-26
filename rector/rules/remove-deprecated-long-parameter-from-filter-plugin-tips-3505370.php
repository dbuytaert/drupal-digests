<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3505370
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In drupal:11.4.0, the $long argument to FilterInterface::tips() was
// deprecated and will be removed in drupal:12.0.0. Only short tips are
// used going forward. This rule removes the $long parameter from any
// tips() override and replaces all references to $long in the method
// body with the literal false, preserving the short-format behaviour.
//
// Before:
//   public function tips($long = FALSE) {
//       if (!$long) {
//           return $this->t('Short tip text.');
//       }
//       return $this->t('Long tip text with more details.');
//   }
//
// After:
//   public function tips() {
//       if (!false) {
//           return $this->t('Short tip text.');
//       }
//       return $this->t('Long tip text with more details.');
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated $long parameter from filter plugin tips() methods.
 *
 * In drupal:11.4.0 the $long argument to FilterInterface::tips() was
 * deprecated and will be removed in drupal:12.0.0. This rule removes the
 * parameter from the method signature and replaces all uses of $long in the
 * body with the literal false, preserving the existing short-format behaviour.
 *
 * @see https://www.drupal.org/node/3567879
 */
final class RemoveFilterTipsLongParamRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $long parameter from filter plugin tips() methods (drupal:11.4.0)',
            [
                new CodeSample(
                    <<<'CODE'
public function tips($long = FALSE) {
    if (!$long) {
        return $this->t('Short tip text.');
    }
    return $this->t('Long tip text with more details.');
}
CODE,
                    <<<'CODE'
public function tips() {
    if (!false) {
        return $this->t('Short tip text.');
    }
    return $this->t('Long tip text with more details.');
}
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /** @param ClassMethod $node */
    public function refactor(Node $node): ?Node
    {
        // Only target the tips() method.
        if (!$this->isName($node, 'tips')) {
            return null;
        }

        // Find the index of the $long parameter.
        $longParamIndex = null;
        foreach ($node->params as $index => $param) {
            if ($param->var instanceof Variable && $this->getName($param->var) === 'long') {
                $longParamIndex = $index;
                break;
            }
        }

        if ($longParamIndex === null) {
            return null;
        }

        // Remove the $long parameter from the signature.
        array_splice($node->params, $longParamIndex, 1);

        // Replace all $long variable usages in the method body with false.
        if ($node->stmts !== null) {
            $traverser = new NodeTraverser();
            $traverser->addVisitor(new class extends NodeVisitorAbstract {
                public function leaveNode(Node $node): ?Node
                {
                    if ($node instanceof Variable && $node->name === 'long') {
                        return new Node\Expr\ConstFetch(
                            new Node\Name('false')
                        );
                    }
                    return null;
                }
            });
            $node->stmts = $traverser->traverse($node->stmts);
        }

        return $node;
    }
}
