<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Renames the deprecated hook_ranking() OOP hook attribute argument
 * 'ranking' to 'node_search_ranking' as required by the Drupal 11.3
 * deprecation (removed in 12.0). Any class method decorated with
 * #[Hook('ranking')] from Drupal\Core\Hook\Attribute\Hook must be
 * updated to #[Hook('node_search_ranking')] to avoid the deprecation
 * warning.
 *
 * Before:
 *   #[Hook('ranking')]
 *   public function ranking(): array { return []; }
 *
 * After:
 *   #[Hook('node_search_ranking')]
 *   public function ranking(): array { return []; }
 *
 * Caveats:
 *   The rule only changes the #[Hook] attribute argument. It does not
 *   rename the implementing method itself (the method name is not
 *   constrained by the hook system), nor does it update any @deprecated
 *   or @see docblock text.
 *
 * @see https://www.drupal.org/node/1019966
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameHookRankingRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename #[Hook(\'ranking\')] to #[Hook(\'node_search_ranking\')] following the deprecation of hook_ranking() in Drupal 11.3.',
            [new CodeSample(
                '#[Hook(\'ranking\')]
public function ranking(): array { return []; }',
                '#[Hook(\'node_search_ranking\')]
public function ranking(): array { return []; }',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /** @param Attribute $node */
    public function refactor(Node $node): ?Node
    {
        // Only target \Drupal\Core\Hook\Attribute\Hook attributes.
        if ($this->getName($node->name) !== 'Drupal\\Core\\Hook\\Attribute\\Hook') {
            return null;
        }

        if ($node->args === []) {
            return null;
        }

        $firstArg = $node->args[0]->value;
        if (!$firstArg instanceof String_) {
            return null;
        }

        if ($firstArg->value !== 'ranking') {
            return null;
        }

        $firstArg->value = 'node_search_ranking';
        return $node;
    }
}
