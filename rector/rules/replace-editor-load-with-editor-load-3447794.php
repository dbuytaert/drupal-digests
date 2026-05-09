<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated global function
 * editor_load($format_id) with
 * \Drupal\editor\Entity\Editor::load($format_id). The function was
 * deprecated in Drupal 11.2.0 and will be removed in 12.0.0; using the
 * entity static load method is equivalent and avoids a full
 * loadMultiple() that previously triggered an unnecessary
 * ConfigFactory::listAll() query.
 *
 * Before:
 *   $editor = editor_load($format_id);
 *
 * After:
 *   $editor = \Drupal\editor\Entity\Editor::load($format_id);
 *
 * Caveats:
 *   The original editor_load() short-circuited on falsy $format_id
 *   values (returning NULL without calling load). Editor::load(null)
 *   also returns NULL in practice (no entity has a null ID), so
 *   behavior is equivalent for all realistic call sites. Code that
 *   deliberately passes NULL or 0 as a format ID is an unusual edge
 *   case the rule handles safely.
 *
 * @see https://www.drupal.org/node/3447794
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class EditorLoadToEditorEntityLoadRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated editor_load() with \Drupal\editor\Entity\Editor::load().',
            [new CodeSample(
                '$editor = editor_load($format_id);',
                '$editor = \Drupal\editor\Entity\Editor::load($format_id);',
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
        if (!$this->isName($node->name, 'editor_load')) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        return new StaticCall(
            new FullyQualified('Drupal\\editor\\Entity\\Editor'),
            'load',
            $node->args,
        );
    }
}
