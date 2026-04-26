<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3258581
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites use import statements that reference the deprecated
// Drupal\content_translation\Plugin\migrate\source\I18nQueryTrait to use
// the replacement
// Drupal\migrate_drupal\Plugin\migrate\source\I18nQueryTrait. The trait
// was moved to migrate_drupal in Drupal 11.2.0 because it belongs to the
// migrate ecosystem, not content translation. The old location is
// removed in Drupal 12.0.0.
//
// Before:
//   use Drupal\content_translation\Plugin\migrate\source\I18nQueryTrait;
//
// After:
//   use Drupal\migrate_drupal\Plugin\migrate\source\I18nQueryTrait;


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\UseUse;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated I18nQueryTrait from content_translation
 * with the new location in migrate_drupal (deprecated drupal:11.2.0,
 * removed drupal:12.0.0).
 */
final class RenameI18nQueryTraitRector extends AbstractRector
{
    private const OLD_CLASS = 'Drupal\\content_translation\\Plugin\\migrate\\source\\I18nQueryTrait';
    private const NEW_CLASS = 'Drupal\\migrate_drupal\\Plugin\\migrate\\source\\I18nQueryTrait';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated Drupal\\content_translation\\Plugin\\migrate\\source\\I18nQueryTrait with Drupal\\migrate_drupal\\Plugin\\migrate\\source\\I18nQueryTrait',
            [
                new CodeSample(
                    'use Drupal\\content_translation\\Plugin\\migrate\\source\\I18nQueryTrait;',
                    'use Drupal\\migrate_drupal\\Plugin\\migrate\\source\\I18nQueryTrait;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [UseUse::class];
    }

    /** @param UseUse $node */
    public function refactor(Node $node): ?Node
    {
        $name = $node->name->toString();
        if ($name !== self::OLD_CLASS) {
            return null;
        }

        $node->name = new Name(self::NEW_CLASS);
        return $node;
    }
}
