<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/2010202
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to the deprecated comment_uri(CommentInterface
// $comment) procedural function (deprecated in Drupal 11.3.0, removed in
// 12.0.0) to the equivalent $comment->permalink() method call. The
// procedural function was a leftover uri_callback that now simply
// delegates to Comment::permalink(), so the rewrite is a direct one-to-
// one substitution.
//
// Before:
//   $url = comment_uri($comment);
//
// After:
//   $url = $comment->permalink();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CommentUriToPermalinkRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated comment_uri($comment) calls with $comment->permalink()',
            [
                new CodeSample(
                    'comment_uri($comment);',
                    '$comment->permalink();'
                ),
            ]
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
        if (!$this->isName($node, 'comment_uri')) {
            return null;
        }

        if (count($node->args) !== 1) {
            return null;
        }

        $commentArg = $node->args[0]->value;

        return $this->nodeFactory->createMethodCall($commentArg, 'permalink');
    }
}
