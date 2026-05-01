<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated comment_uri($comment) procedural
 * function to the equivalent object method call $comment->permalink().
 * The function was deprecated in Drupal 11.3.0 and will be removed in
 * Drupal 12.0.0. Contrib and custom modules that generate comment URLs
 * via the old function need this rewrite to stay compatible.
 *
 * Before:
 *   $url = comment_uri($comment);
 *
 * After:
 *   $url = $comment->permalink();
 *
 * @see https://www.drupal.org/node/2010202
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
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

        // comment_uri() requires exactly one argument: the CommentInterface object.
        if (count($node->args) !== 1) {
            return null;
        }

        $arg = $node->args[0];
        if (!$arg instanceof \PhpParser\Node\Arg) {
            return null;
        }

        // Replace comment_uri($comment) with $comment->permalink()
        return new MethodCall(
            $arg->value,
            new Identifier('permalink'),
        );
    }
}
