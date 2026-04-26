<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3568124
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated procedural function
// media_filter_format_edit_form_validate() (removed in Drupal 12.0.0)
// with the equivalent
// \Drupal\media\Hook\MediaHooks::formatEditFormValidate() service
// method. Handles both direct function calls and string-based form
// #validate callbacks. See https://www.drupal.org/node/3566774.
//
// Before:
//   // As a form validate callback string:
//   $form['#validate'][] = 'media_filter_format_edit_form_validate';
//   
//   // As a direct call:
//   media_filter_format_edit_form_validate($form, $form_state);
//
// After:
//   // As a form validate callback array:
//   $form['#validate'][] = [\Drupal\media\Hook\MediaHooks::class, 'formatEditFormValidate'];
//   
//   // As a direct call:
//   \Drupal::service(\Drupal\media\Hook\MediaHooks::class)->formatEditFormValidate($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

/**
 * Replaces deprecated media_filter_format_edit_form_validate() function calls.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:12.0.0.
 * Replacement: \Drupal\media\Hook\MediaHooks::formatEditFormValidate()
 * See https://www.drupal.org/node/3566774
 */
final class MediaFilterFormatEditFormValidateRector extends AbstractRector
{
    private const DEPRECATED_FUNCTION = 'media_filter_format_edit_form_validate';
    private const MEDIA_HOOKS_CLASS = 'Drupal\\media\\Hook\\MediaHooks';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated media_filter_format_edit_form_validate() with \\Drupal\\media\\Hook\\MediaHooks::formatEditFormValidate().',
            [
                new CodeSample(
                    'media_filter_format_edit_form_validate($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\media\\Hook\\MediaHooks::class)->formatEditFormValidate($form, $form_state);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class, String_::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Handle direct function call: media_filter_format_edit_form_validate($form, $form_state)
        if ($node instanceof FuncCall) {
            if (!$this->isName($node, self::DEPRECATED_FUNCTION)) {
                return null;
            }

            // Build: \Drupal::service(\Drupal\media\Hook\MediaHooks::class)
            $drupalService = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [
                    new Arg(
                        new ClassConstFetch(
                            new FullyQualified(self::MEDIA_HOOKS_CLASS),
                            'class'
                        )
                    ),
                ]
            );

            // Build: ->formatEditFormValidate($form, $form_state)
            return new MethodCall(
                $drupalService,
                'formatEditFormValidate',
                $node->args
            );
        }

        // Handle string callback: 'media_filter_format_edit_form_validate'
        if ($node instanceof String_) {
            if ($node->value !== self::DEPRECATED_FUNCTION) {
                return null;
            }

            // Replace with [\Drupal\media\Hook\MediaHooks::class, 'formatEditFormValidate']
            return new Node\Expr\Array_([
                new Node\Expr\ArrayItem(
                    new ClassConstFetch(
                        new FullyQualified(self::MEDIA_HOOKS_CLASS),
                        'class'
                    )
                ),
                new Node\Expr\ArrayItem(
                    new String_('formatEditFormValidate')
                ),
            ]);
        }

        return null;
    }
}

use Rector\Config\RectorConfig;
