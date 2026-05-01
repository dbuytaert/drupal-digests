<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Removes the 6th $tag argument from new TwigNodeTrans(...) constructor
 * calls. Twig 3.12 deprecated the $tag constructor argument of
 * Drupal\Core\Template\TwigNodeTrans, and Drupal core subsequently
 * removed the parameter entirely (issue #3473440). Contrib token parsers
 * that subclass Twig\TokenParser\AbstractTokenParser and pass
 * $this->getTag() as the final argument must drop it to avoid fatal
 * errors.
 *
 * Before:
 *   new TwigNodeTrans($body, $plural, $count, $options, $lineno, $this->getTag());
 *
 * After:
 *   new TwigNodeTrans($body, $plural, $count, $options, $lineno);
 *
 * Caveats:
 *   Only targets direct new TwigNodeTrans(...) calls with 6 or more
 *   arguments. Does not rewrite subclass __construct overrides that
 *   still declare a $tag parameter in their own signature; those must
 *   be updated manually.
 *
 * @see https://www.drupal.org/node/3473440
 */


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated $tag argument from new TwigNodeTrans() calls.
 *
 * Twig 3.12 deprecated the $tag constructor argument of TwigNodeTrans and the
 * parameter was subsequently removed from the class in Drupal core.
 */
final class RemoveTwigNodeTransTagArgRector extends AbstractRector
{
    private const TARGET_CLASS = 'Drupal\\Core\\Template\\TwigNodeTrans';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the deprecated $tag argument from new TwigNodeTrans() constructor calls.',
            [new CodeSample(
                'new TwigNodeTrans($body, $plural, $count, $options, $lineno, $this->getTag());',
                'new TwigNodeTrans($body, $plural, $count, $options, $lineno);',
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
        if (!$this->isName($node->class, self::TARGET_CLASS)) {
            return null;
        }

        // The $tag argument is the 6th argument (index 5).
        if (!isset($node->args[5])) {
            return null;
        }

        // Remove the 6th argument and any beyond it (only $tag expected).
        array_splice($node->args, 5);

        return $node;
    }
}
