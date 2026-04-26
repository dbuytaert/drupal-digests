<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3577671
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated
// locale_translate_get_interface_translation_files() procedural function
// with an equivalent call to \Drupal::service(LocaleFileManager::class)-
// >getInterfaceTranslationFiles(). The function was deprecated in Drupal
// 11.4.0 and will be removed in 13.0.0. The arguments and return value
// are identical, making this a safe automated rewrite.
//
// Before:
//   $files = locale_translate_get_interface_translation_files($projects, $langcodes);
//
// After:
//   $files = \Drupal::service(\Drupal\locale\File\LocaleFileManager::class)->getInterfaceTranslationFiles($projects, $langcodes);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LocaleTranslateGetInterfaceTranslationFilesRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale_translate_get_interface_translation_files() with \\Drupal::service(LocaleFileManager::class)->getInterfaceTranslationFiles()',
            [
                new CodeSample(
                    'locale_translate_get_interface_translation_files($projects, $langcodes);',
                    '\\Drupal::service(\\Drupal\\locale\\File\\LocaleFileManager::class)->getInterfaceTranslationFiles($projects, $langcodes);'
                ),
            ]
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
        if (!$this->isName($node, 'locale_translate_get_interface_translation_files')) {
            return null;
        }

        // Build: \Drupal::service(\Drupal\locale\File\LocaleFileManager::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [
                new Arg(new \PhpParser\Node\Expr\ClassConstFetch(
                    new FullyQualified('Drupal\\locale\\File\\LocaleFileManager'),
                    'class'
                )),
            ]
        );

        // Build: ->getInterfaceTranslationFiles(...original args...)
        return new MethodCall($serviceCall, 'getInterfaceTranslationFiles', $node->args);
    }
}
