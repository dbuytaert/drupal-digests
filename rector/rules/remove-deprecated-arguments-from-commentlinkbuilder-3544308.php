<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites the 5-argument form of new CommentLinkBuilder(...) to the new
 * 3-argument form introduced in Drupal 11.3. The $module_handler (arg 2)
 * and $entity_type_manager (arg 4) parameters were deprecated and
 * removed; $string_translation shifts from position 3 to position 2.
 *
 * Before:
 *   new \Drupal\comment\CommentLinkBuilder($currentUser, $commentManager, $moduleHandler, $stringTranslation, $entityTypeManager)
 *
 * After:
 *   new \Drupal\comment\CommentLinkBuilder($currentUser, $commentManager, $stringTranslation)
 *
 * Caveats:
 *   Only rewrites calls with exactly 5 arguments. Calls using named
 *   arguments or passing fewer/more arguments are not handled.
 *
 * @see https://www.drupal.org/node/3544308
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CommentLinkBuilderConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $module_handler and $entity_type_manager arguments from CommentLinkBuilder::__construct()',
            [new CodeSample(
                'new \\Drupal\\comment\\CommentLinkBuilder($currentUser, $commentManager, $moduleHandler, $stringTranslation, $entityTypeManager)',
                'new \\Drupal\\comment\\CommentLinkBuilder($currentUser, $commentManager, $stringTranslation)',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->class, 'Drupal\\comment\\CommentLinkBuilder')) {
            return null;
        }
        if (count($node->args) !== 5) {
            return null;
        }
        // Old signature: ($current_user, $comment_manager, $module_handler, $string_translation, $entity_type_manager)
        // New signature: ($current_user, $comment_manager, $string_translation)
        $node->args = [$node->args[0], $node->args[1], $node->args[3]];
        return $node;
    }
}
