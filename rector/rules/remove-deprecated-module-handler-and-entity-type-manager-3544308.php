<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3544308
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to CommentLinkBuilder::__construct() that use the old
// 5-argument signature ($current_user, $comment_manager,
// $module_handler, $string_translation, $entity_type_manager) to the new
// 3-argument signature ($current_user, $comment_manager,
// $string_translation). The $module_handler (third position) and
// $entity_type_manager (fifth position) parameters were removed in
// drupal:11.3.0 because they were unused after comment libraries moved
// to the history module.
//
// Before:
//   new \Drupal\comment\CommentLinkBuilder($currentUser, $commentManager, $moduleHandler, $stringTranslation, $entityTypeManager);
//
// After:
//   new \Drupal\comment\CommentLinkBuilder($currentUser, $commentManager, $stringTranslation);


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites the deprecated 5-argument CommentLinkBuilder constructor call.
 *
 * The $module_handler (position 2) and $entity_type_manager (position 4)
 * arguments were removed in drupal:11.3.0.
 * See https://www.drupal.org/node/3544527
 */
final class CommentLinkBuilderConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $module_handler and $entity_type_manager arguments from CommentLinkBuilder::__construct()',
            [
                new CodeSample(
                    'new \\Drupal\\comment\\CommentLinkBuilder($currentUser, $commentManager, $moduleHandler, $stringTranslation, $entityTypeManager);',
                    'new \\Drupal\\comment\\CommentLinkBuilder($currentUser, $commentManager, $stringTranslation);'
                ),
            ]
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
        if (!$node->class instanceof Name) {
            return null;
        }

        $className = $this->getName($node->class);
        if ($className !== 'Drupal\\comment\\CommentLinkBuilder'
            && $className !== 'CommentLinkBuilder'
        ) {
            return null;
        }

        // Only rewrite the deprecated 5-argument form.
        // Old: ($current_user, $comment_manager, $module_handler, $string_translation, $entity_type_manager)
        // New: ($current_user, $comment_manager, $string_translation)
        if (count($node->args) !== 5) {
            return null;
        }

        // Keep arg[0] ($current_user), arg[1] ($comment_manager), arg[3] ($string_translation).
        // Drop arg[2] ($module_handler) and arg[4] ($entity_type_manager).
        $node->args = [
            $node->args[0],
            $node->args[1],
            $node->args[3],
        ];

        return $node;
    }
}
