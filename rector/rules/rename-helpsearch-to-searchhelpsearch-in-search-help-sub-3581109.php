<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3581109
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal core moved Drupal\help\Plugin\Search\HelpSearch to a new
// search_help sub-module and renamed it
// Drupal\search_help\Plugin\Search\SearchHelpSearch. This rule updates
// use import statements and any fully-qualified class references in
// contrib or custom code that extended or instantiated the old
// HelpSearch plugin class.
//
// Before:
//   use Drupal\help\Plugin\Search\HelpSearch;
//   
//   $plugin = HelpSearch::create($container, [], 'help_search', []);
//
// After:
//   use Drupal\search_help\Plugin\Search\SearchHelpSearch;
//   
//   $plugin = \Drupal\search_help\Plugin\Search\SearchHelpSearch::create($container, [], 'help_search', []);


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\UseItem;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames Drupal\help\Plugin\Search\HelpSearch to
 * Drupal\search_help\Plugin\Search\SearchHelpSearch.
 *
 * In Drupal core the HelpSearch plugin was moved from the help module to a new
 * search_help sub-module and renamed to SearchHelpSearch. This rule updates
 * fully-qualified class names and use-statement imports.
 */
final class RenameHelpSearchToSearchHelpSearchRector extends AbstractRector
{
    private const OLD_CLASS = 'Drupal\help\Plugin\Search\HelpSearch';
    private const NEW_CLASS = 'Drupal\search_help\Plugin\Search\SearchHelpSearch';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename Drupal\help\Plugin\Search\HelpSearch to Drupal\search_help\Plugin\Search\SearchHelpSearch',
            [
                new CodeSample(
                    'use Drupal\help\Plugin\Search\HelpSearch;',
                    'use Drupal\search_help\Plugin\Search\SearchHelpSearch;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [UseItem::class, Name\FullyQualified::class];
    }

    /**
     * @param UseItem|Name\FullyQualified $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof UseItem) {
            if ($node->name->toString() === self::OLD_CLASS) {
                $node->name = new Name\FullyQualified(self::NEW_CLASS, $node->name->getAttributes());
                // Clear alias if it was the auto-derived short name HelpSearch
                if ($node->alias !== null && $node->alias->toString() === 'HelpSearch') {
                    $node->alias = null;
                }
                return $node;
            }
            return null;
        }

        if ($node instanceof Name\FullyQualified) {
            if ($node->toString() === self::OLD_CLASS) {
                return new Name\FullyQualified(self::NEW_CLASS, $node->getAttributes());
            }
        }

        return null;
    }
}
