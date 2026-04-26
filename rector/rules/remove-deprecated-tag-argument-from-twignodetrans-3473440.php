<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3473440
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Since twig/twig 3.12, the 6th $tag constructor argument of
// Drupal\Core\Template\TwigNodeTrans is deprecated and ignored. Drupal
// core removed this parameter in issue #3473440. Contrib modules with
// custom TokenParser implementations that pass $this->getTag() as the
// 6th argument need this argument dropped.
//
// Before:
//   new TwigNodeTrans($body, $plural, $count, $options, $lineno, $this->getTag());
//
// After:
//   new TwigNodeTrans($body, $plural, $count, $options, $lineno);


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated 6th $tag constructor argument from TwigNodeTrans.
 *
 * Since twig/twig 3.12, the "tag" constructor argument of
 * Drupal\Core\Template\TwigNodeTrans is deprecated and ignored. Drupal core
 * fixed this in #3473440 by removing the parameter from the constructor.
 */
final class RemoveTwigNodeTransTagArgumentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove the deprecated 6th $tag argument from TwigNodeTrans constructor calls',
            [
                new CodeSample(
                    'new TwigNodeTrans($body, $plural, $count, $options, $lineno, $this->getTag());',
                    'new TwigNodeTrans($body, $plural, $count, $options, $lineno);'
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
        // Match both fully-qualified and imported class names.
        if ($className !== 'TwigNodeTrans'
            && $className !== 'Drupal\\Core\\Template\\TwigNodeTrans') {
            return null;
        }

        // The deprecated form has a 6th argument ($tag).
        if (count($node->args) !== 6) {
            return null;
        }

        // Remove the 6th argument.
        array_pop($node->args);

        return $node;
    }
}
