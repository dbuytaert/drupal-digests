<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates ten global constants in locale.module
 * (LOCALE_CUSTOMIZED, LOCALE_NOT_CUSTOMIZED,
 * LOCALE_TRANSLATION_USE_SOURCE_*, LOCALE_TRANSLATION_OVERWRITE_*,
 * LOCALE_TRANSLATION_REMOTE, LOCALE_TRANSLATION_LOCAL,
 * LOCALE_TRANSLATION_CURRENT) in favor of LocaleDefaultOptions class
 * constants and TranslationUpdateMode/Overwrite/SourceType backed-enum
 * cases. This rule rewrites any ConstFetch reference to one of these ten
 * global constants into the corresponding ClassConstFetch or
 * EnumCase->value expression, so contrib and custom code using these
 * constants keeps working after they are removed.
 *
 * Before:
 *   $customized = LOCALE_CUSTOMIZED;
 *   $use_source = LOCALE_TRANSLATION_USE_SOURCE_LOCAL;
 *   if ($source->type == LOCALE_TRANSLATION_LOCAL || $source->type == LOCALE_TRANSLATION_REMOTE) {
 *     // ...
 *   }
 *
 * After:
 *   $customized = \Drupal\locale\LocaleDefaultOptions::CUSTOMIZED;
 *   $use_source = \Drupal\locale\Model\TranslationUpdateMode::Local->value;
 *   if ($source->type == \Drupal\locale\Model\SourceType::Local->value || $source->type == \Drupal\locale\Model\SourceType::Remote->value) {
 *     // ...
 *   }
 *
 * Caveats:
 *   Does not touch LOCALE_TRANSLATION_STATUS_TTL (inlined as a literal
 *   600, no direct replacement) or
 *   LOCALE_JS_STRING/LOCALE_JS_OBJECT/LOCALE_JS_OBJECT_CONTEXT (moved
 *   to local variables inside LocaleJs::parseJsFile(), no public
 *   replacement); these have no equivalent expression to substitute, so
 *   call sites using them need manual review.
 *
 * @see https://www.drupal.org/node/2831617
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceLocaleDeprecatedConstantsRector extends AbstractRector
{
    /**
     * Old global constant name => [FQCN, member name, isEnumCase].
     *
     * @var array<string, array{0: string, 1: string, 2: bool}>
     */
    private const REPLACEMENTS = [
        'LOCALE_NOT_CUSTOMIZED' => ['Drupal\\locale\\LocaleDefaultOptions', 'NOT_CUSTOMIZED', false],
        'LOCALE_CUSTOMIZED' => ['Drupal\\locale\\LocaleDefaultOptions', 'CUSTOMIZED', false],
        'LOCALE_TRANSLATION_USE_SOURCE_LOCAL' => ['Drupal\\locale\\Model\\TranslationUpdateMode', 'Local', true],
        'LOCALE_TRANSLATION_USE_SOURCE_REMOTE_AND_LOCAL' => ['Drupal\\locale\\Model\\TranslationUpdateMode', 'RemoteAndLocal', true],
        'LOCALE_TRANSLATION_OVERWRITE_ALL' => ['Drupal\\locale\\Model\\Overwrite', 'All', true],
        'LOCALE_TRANSLATION_OVERWRITE_NON_CUSTOMIZED' => ['Drupal\\locale\\Model\\Overwrite', 'NonCustomized', true],
        'LOCALE_TRANSLATION_OVERWRITE_NONE' => ['Drupal\\locale\\Model\\Overwrite', 'None', true],
        'LOCALE_TRANSLATION_REMOTE' => ['Drupal\\locale\\Model\\SourceType', 'Remote', true],
        'LOCALE_TRANSLATION_LOCAL' => ['Drupal\\locale\\Model\\SourceType', 'Local', true],
        'LOCALE_TRANSLATION_CURRENT' => ['Drupal\\locale\\Model\\SourceType', 'Current', true],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale.module global constants with their LocaleDefaultOptions class constant or Model enum case replacements.',
            [new CodeSample(
                '$customized = LOCALE_CUSTOMIZED;
$use_source = LOCALE_TRANSLATION_USE_SOURCE_LOCAL;',
                '$customized = \Drupal\locale\LocaleDefaultOptions::CUSTOMIZED;
$use_source = \Drupal\locale\Model\TranslationUpdateMode::Local->value;',
            )],
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
        if (!$node instanceof ConstFetch) {
            return null;
        }

        foreach (self::REPLACEMENTS as $oldConstName => [$class, $member, $isEnumCase]) {
            if (!$this->isName($node, $oldConstName)) {
                continue;
            }

            $classConstFetch = $this->nodeFactory->createClassConstFetch($class, $member);

            if (!$isEnumCase) {
                return $classConstFetch;
            }

            return new PropertyFetch($classConstFetch, new Identifier('value'));
        }

        return null;
    }
}
