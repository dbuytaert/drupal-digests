<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3570917
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to the procedural
// editor_image_upload_settings_form($editor) to \Drupal::service(\Drupal
// \editor\EditorImageUploadSettings::class)->getForm($editor). The
// function was deprecated in drupal:11.4.0 and is removed in
// drupal:13.0.0 (issue #3570917); its logic was moved to the new
// EditorImageUploadSettings service to enable proper dependency
// injection.
//
// Before:
//   editor_image_upload_settings_form($editor);
//
// After:
//   \Drupal::service(\Drupal\editor\EditorImageUploadSettings::class)->getForm($editor);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated editor_image_upload_settings_form() with
 * \Drupal::service(EditorImageUploadSettings::class)->getForm().
 *
 * The function was deprecated in drupal:11.4.0 and is removed in
 * drupal:13.0.0. The logic was moved to the EditorImageUploadSettings
 * service (issue #3570917).
 *
 * @see https://www.drupal.org/node/3570919
 * @see https://www.drupal.org/project/drupal/issues/3570917
 */
final class ReplaceEditorImageUploadSettingsFormRector extends AbstractRector
{
    private const SERVICE_CLASS = 'Drupal\\editor\\EditorImageUploadSettings';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated editor_image_upload_settings_form() with \\Drupal::service(EditorImageUploadSettings::class)->getForm() (removed in drupal:13.0.0)',
            [
                new CodeSample(
                    'editor_image_upload_settings_form($editor);',
                    '\\Drupal::service(\\Drupal\\editor\\EditorImageUploadSettings::class)->getForm($editor);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall || !$node->name instanceof Name) {
            return null;
        }

        if ($node->name->toString() !== 'editor_image_upload_settings_form') {
            return null;
        }

        // Build: \Drupal::service(EditorImageUploadSettings::class)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified(self::SERVICE_CLASS),
                'class'
            ))]
        );

        // Build: ->getForm(...original args...)
        return new MethodCall($serviceCall, 'getForm', $node->args);
    }
}
