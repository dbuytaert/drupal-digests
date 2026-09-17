<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates the global functions file_save_upload(),
 * file_managed_file_save_upload(), and _file_save_upload_from_form() in
 * favor of the FormFileUploader and ManagedFileElementHelper services.
 * This rule rewrites calls to the equivalent
 * \Drupal::service(...)->method(...) form, preserving argument order.
 * For file_save_upload(), a literal FALSE/NULL third argument (the old
 * $destination default) is rewritten to the string 'temporary://' since
 * the new method's parameter is strictly typed as string.
 *
 * Before:
 *   $file = file_save_upload('upload', $validators, FALSE, 0);
 *   $result = file_managed_file_save_upload($element, $form_state);
 *   $result2 = _file_save_upload_from_form($element, $form_state, 0);
 *
 * After:
 *   $file = \Drupal::service(\Drupal\file\Upload\FormFileUploader::class)->saveFormUploadedFiles('upload', $validators, 'temporary://', 0);
 *   $result = \Drupal::service(\Drupal\file\Upload\ManagedFileElementHelper::class)->managedFileSaveUpload($element, $form_state);
 *   $result2 = \Drupal::service(\Drupal\file\Upload\ManagedFileElementHelper::class)->saveFileUploads($element, $form_state, 0);
 *
 * Caveats:
 *   If the $destination argument to file_save_upload() is a non-literal
 *   expression (a variable or function call) that may evaluate to FALSE
 *   or NULL at runtime, the rule leaves it unchanged; the rewritten
 *   service call requires a string, so such a call would need manual
 *   review. Calls using named arguments or argument unpacking
 *   (...$args) are skipped entirely rather than risk a wrong rewrite.
 *
 * @see https://www.drupal.org/node/3375423
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceDeprecatedFileSaveUploadFunctionsRector extends AbstractRector
{
    /** @var array<string, array{class: string, method: string, minArgs: int, maxArgs: int}> */
    private const REPLACEMENTS = [
        'file_save_upload' => [
            'class' => 'Drupal\\file\\Upload\\FormFileUploader',
            'method' => 'saveFormUploadedFiles',
            'minArgs' => 1,
            'maxArgs' => 5,
        ],
        'file_managed_file_save_upload' => [
            'class' => 'Drupal\\file\\Upload\\ManagedFileElementHelper',
            'method' => 'managedFileSaveUpload',
            'minArgs' => 2,
            'maxArgs' => 2,
        ],
        '_file_save_upload_from_form' => [
            'class' => 'Drupal\\file\\Upload\\ManagedFileElementHelper',
            'method' => 'saveFileUploads',
            'minArgs' => 2,
            'maxArgs' => 4,
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace the deprecated file_save_upload(), file_managed_file_save_upload() and _file_save_upload_from_form() functions with calls to their replacement services.',
            [new CodeSample(
                '$file = file_save_upload("upload", $validators);',
                '$file = \Drupal::service(\Drupal\file\Upload\FormFileUploader::class)->saveFormUploadedFiles("upload", $validators);',
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

        $functionName = null;
        foreach (self::REPLACEMENTS as $name => $replacement) {
            if ($this->isName($node->name, $name)) {
                $functionName = $name;
                break;
            }
        }
        if ($functionName === null) {
            return null;
        }
        $replacement = self::REPLACEMENTS[$functionName];

        $argCount = count($node->args);
        if ($argCount < $replacement['minArgs'] || $argCount > $replacement['maxArgs']) {
            return null;
        }

        // Named arguments and argument unpacking change the mapping between
        // position and parameter; skip rather than risk a wrong rewrite.
        foreach ($node->args as $arg) {
            if (!$arg instanceof Arg || $arg->name !== null || $arg->unpack) {
                return null;
            }
        }

        $args = $node->args;

        if ($functionName === 'file_save_upload') {
            // The old function normalizes a FALSE/NULL $destination to
            // 'temporary://' before delegating; the new method's parameter is
            // a plain string, so a literal FALSE/NULL third argument must be
            // rewritten to the string, or a TypeError results.
            if (isset($args[2]) && $args[2]->value instanceof ConstFetch) {
                $constName = $this->getName($args[2]->value->name);
                if ($constName !== null && in_array(strtolower($constName), ['false', 'null'], true)) {
                    $args[2] = new Arg(new String_('temporary://'));
                }
            }
        }

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified($replacement['class']), 'class'))],
        );

        return new MethodCall($serviceCall, $replacement['method'], $args);
    }
}
