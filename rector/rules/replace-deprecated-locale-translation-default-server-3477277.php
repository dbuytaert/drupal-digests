<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3477277
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the global constant LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN
// with the class constant \Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN.
// The global constant was deprecated in Drupal 11.2.0 and will be
// removed in Drupal 12.0.0 as part of moving the value to a more
// accessible location that does not require locale.module to be loaded.
//
// Before:
//   $pattern = LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN;
//
// After:
//   $pattern = \Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN;


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN constant
 * with \Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN.
 */
final class LocaleTranslationDefaultServerPatternRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN with \\Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN',
            [
                new CodeSample(
                    '$pattern = LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN;',
                    '$pattern = \\Drupal::TRANSLATION_DEFAULT_SERVER_PATTERN;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /** @param ConstFetch $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'LOCALE_TRANSLATION_DEFAULT_SERVER_PATTERN')) {
            return null;
        }

        return new ClassConstFetch(
            new FullyQualified('Drupal'),
            'TRANSLATION_DEFAULT_SERVER_PATTERN'
        );
    }
}
