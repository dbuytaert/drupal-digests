<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global function comment_uri($comment)
 * to the equivalent method call $comment->permalink(). The function was
 * deprecated in drupal:11.3.0 and will be removed in drupal:12.0.0. Any
 * contrib or custom code that still calls comment_uri() directly must
 * switch to the CommentInterface::permalink() method.
 *
 * Before:
 *   $url = comment_uri($comment);
 *
 * After:
 *   $url = $comment->permalink();
 *
 * Caveats:
 *   Only rewrites calls where the argument's type is resolvable as
 *   Drupal\comment\CommentInterface. Calls where PHPStan cannot infer
 *   the argument type are left unchanged and require manual review.
 *
 * @see https://www.drupal.org/node/2010202
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CommentUriToPermalinkRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated comment_uri($comment) with $comment->permalink().',
            [new CodeSample(
                '$url = comment_uri($comment);',
                '$url = $comment->permalink();',
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
        if (!$this->isName($node->name, 'comment_uri')) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        $arg = $node->args[0];
        if (!$arg instanceof Arg) {
            return null;
        }
        if (!$this->isObjectType($arg->value, new ObjectType('Drupal\\comment\\CommentInterface'))) {
            return null;
        }
        return new MethodCall($arg->value, 'permalink');
    }
}
