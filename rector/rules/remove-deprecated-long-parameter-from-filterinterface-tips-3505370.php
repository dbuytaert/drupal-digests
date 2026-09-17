<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Remove deprecated $long parameter from FilterInterface::tips()
 * implementations and from _filter_tips() calls.
 *
 * Before:
 *   public function tips($long = FALSE) { return $this->t('Short tip.'); }
 *
 * After:
 *   public function tips() { return $this->t('Short tip.'); }
 *
 * @see https://www.drupal.org/node/3505370
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveFilterTipsLongParamRector extends AbstractRector
{
    // Both FQCNs and short names are matched so that the rule works whether or
    // not Rector's NameResolver has fully resolved imports.
    private const FILTER_SYMBOLS = [
        'Drupal\\filter\\Plugin\\FilterBase',
        'Drupal\\filter\\Plugin\\FilterInterface',
        'FilterBase',
        'FilterInterface',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $long parameter from FilterInterface::tips() implementations and from _filter_tips() calls.',
            [new CodeSample(
                'public function tips($long = FALSE) { return $this->t(\'Short tip.\'); }',
                'public function tips() { return $this->t(\'Short tip.\'); }',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class, FuncCall::class];
    }

    /** @param Class_|FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }
        return $this->refactorFuncCall($node);
    }

    private function refactorClass(Class_ $node): ?Class_
    {
        if (!$this->classIsFilterPlugin($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            if ($this->updateTipsMethod($method)) {
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    private function classIsFilterPlugin(Class_ $classNode): bool
    {
        foreach ($classNode->implements as $implement) {
            if ($this->nameMatchesFilterSymbol($implement)) {
                return true;
            }
        }
        if ($classNode->extends !== null && $this->nameMatchesFilterSymbol($classNode->extends)) {
            return true;
        }
        return false;
    }

    private function nameMatchesFilterSymbol(Name $name): bool
    {
        return in_array($name->toString(), self::FILTER_SYMBOLS, true);
    }

    private function updateTipsMethod(ClassMethod $method): bool
    {
        if (!$this->isName($method, 'tips')) {
            return false;
        }
        if (count($method->params) === 0) {
            return false;
        }
        $firstParam = $method->params[0];
        if (!($firstParam->var instanceof Variable)) {
            return false;
        }
        if (!$this->isName($firstParam->var, 'long')) {
            return false;
        }

        // Skip when $long is referenced in the body — the long-format branch
        // needs manual removal.
        $usesLong = false;
        $this->traverseNodesWithCallable((array) $method->stmts, function (Node $subNode) use (&$usesLong): void {
            if ($subNode instanceof Variable && $this->isName($subNode, 'long')) {
                $usesLong = true;
            }
        });
        if ($usesLong) {
            return false;
        }

        $method->params = [];
        return true;
    }

    private function refactorFuncCall(FuncCall $node): ?FuncCall
    {
        if (!$this->isName($node->name, '_filter_tips')) {
            return null;
        }
        if (count($node->getArgs()) < 2) {
            return null;
        }
        array_splice($node->args, 1, 1);
        return $node;
    }
}
