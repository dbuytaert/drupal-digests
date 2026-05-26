<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated
 * locale_translation_update_file_history($source) and
 * locale_translation_file_history_delete($projects, $langcodes) global
 * functions with calls to the CurrentImportStorage service.
 * locale_translation_update_file_history wraps the source in
 * CurrentImport::createFromSource() before passing it to ->save().
 * locale_translation_file_history_delete passes its arguments directly
 * to ->delete(). Note: locale_translation_get_file_history() has no
 * direct replacement and is not handled.
 *
 * Before:
 *   locale_translation_update_file_history($source);
 *   locale_translation_file_history_delete($projects, $langcodes);
 *
 * After:
 *   \Drupal::service(\Drupal\locale\CurrentImportStorage::class)->save(\Drupal\locale\CurrentImport::createFromSource($source));
 *   \Drupal::service(\Drupal\locale\CurrentImportStorage::class)->delete($projects, $langcodes);
 *
 * Caveats:
 *   Zero-argument calls to locale_translation_file_history_delete() are
 *   not rewritten because the replacement
 *   CurrentImportStorage::delete() requires the $projects argument.
 *   Zero-argument calls to locale_translation_update_file_history() are
 *   also skipped. The third deprecated function
 *   locale_translation_get_file_history() has no direct mechanical
 *   replacement and is not handled.
 *
 * @see https://www.drupal.org/node/3037156
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LocaleTranslationHistoryFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale_translation_update_file_history() and locale_translation_file_history_delete() with CurrentImportStorage service calls.',
            [new CodeSample(
                'locale_translation_update_file_history($source);',
                '\Drupal::service(\Drupal\locale\CurrentImportStorage::class)->save(\Drupal\locale\CurrentImport::createFromSource($source));',
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
        if (!$node instanceof FuncCall) {
            return null;
        }

        if ($this->isName($node->name, 'locale_translation_update_file_history')) {
            if (count($node->args) !== 1) {
                return null;
            }
            $service = $this->buildServiceCall('Drupal\\locale\\CurrentImportStorage');
            $wrappedSource = new StaticCall(
                new FullyQualified('Drupal\\locale\\CurrentImport'),
                new Identifier('createFromSource'),
                [$node->args[0]]
            );
            return new MethodCall($service, new Identifier('save'), [new Arg($wrappedSource)]);
        }

        if ($this->isName($node->name, 'locale_translation_file_history_delete')) {
            // Require at least 1 arg; the service delete() method requires $projects.
            if (count($node->args) < 1) {
                return null;
            }
            $service = $this->buildServiceCall('Drupal\\locale\\CurrentImportStorage');
            return new MethodCall($service, new Identifier('delete'), $node->args);
        }

        return null;
    }

    private function buildServiceCall(string $serviceClass): StaticCall
    {
        $classRef = new ClassConstFetch(
            new FullyQualified($serviceClass),
            new Identifier('class')
        );
        return new StaticCall(
            new FullyQualified('Drupal'),
            new Identifier('service'),
            [new Arg($classRef)]
        );
    }
}
