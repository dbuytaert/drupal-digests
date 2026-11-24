<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global function form_select_options()
 * to the new static method
 * \Drupal\Core\Form\FormSelectOptions::process(), which has the same
 * signature and behavior. The function is deprecated in drupal:11.6.0
 * and removed from drupal:13.0.0. Calls using named arguments, argument
 * unpacking or first-class callable syntax are skipped.
 *
 * Before:
 *   $options = form_select_options($element);
 *
 * After:
 *   $options = \Drupal\Core\Form\FormSelectOptions::process($element);
 *
 * Caveats:
 *   form_get_options() is also deprecated in the same issue but has no
 *   replacement, so it is not rewritten. Calls with named arguments,
 *   unpacking or first-class callable syntax are left for manual
 *   review.
 *
 * @see https://www.drupal.org/node/2448545
 * @deprecated drupal:11.6.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceFormSelectOptionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace form_select_options() with FormSelectOptions::process().',
            [new CodeSample(
                '$options = form_select_options($element);',
                '$options = \\Drupal\\Core\\Form\\FormSelectOptions::process($element);',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->name instanceof Node\Name || !$this->isName($node->name, 'form_select_options')) {
            return null;
        }
        if ($node->isFirstClassCallable()) {
            return null;
        }
        $args = $node->getArgs();
        if (count($args) < 1 || count($args) > 2) {
            return null;
        }
        foreach ($args as $arg) {
            if ($arg->unpack || $arg->name !== null) {
                return null;
            }
        }
        return new StaticCall(
            new FullyQualified('Drupal\\Core\\Form\\FormSelectOptions'),
            'process',
            $args,
        );
    }
}
