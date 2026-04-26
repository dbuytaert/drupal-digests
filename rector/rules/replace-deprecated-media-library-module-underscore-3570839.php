<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3570839
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites four internal procedural functions from media_library.module
// deprecated in drupal:11.4.0 and removed in drupal:12.0.0 (issue
// #3570839). _media_library_configure_form_display($type) and
// _media_library_configure_view_display($type) become static calls on
// MediaLibraryDisplayManager. _media_library_media_type_form_submit()
// and _media_library_views_form_media_library_after_build() become
// service method calls on MediaLibraryHooks.
//
// Before:
//   _media_library_configure_form_display($type);
//   _media_library_configure_view_display($type);
//   _media_library_media_type_form_submit($form, $form_state);
//   _media_library_views_form_media_library_after_build($form, $form_state);
//
// After:
//   \Drupal\media_library\MediaLibraryDisplayManager::configureFormDisplay($type);
//   \Drupal\media_library\MediaLibraryDisplayManager::configureViewDisplay($type);
//   \Drupal::service(\Drupal\media_library\Hook\MediaLibraryHooks::class)->mediaTypeFormSubmit($form, $form_state);
//   \Drupal::service(\Drupal\media_library\Hook\MediaLibraryHooks::class)->viewsFormAfterBuild($form, $form_state);


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
 * Replaces four deprecated underscore functions from media_library.module
 * with their OO successors.
 *
 * - _media_library_views_form_media_library_after_build()
 *     => MediaLibraryHooks::viewsFormAfterBuild()
 * - _media_library_media_type_form_submit()
 *     => MediaLibraryHooks::mediaTypeFormSubmit()
 * - _media_library_configure_form_display()
 *     => MediaLibraryDisplayManager::configureFormDisplay()
 * - _media_library_configure_view_display()
 *     => MediaLibraryDisplayManager::configureViewDisplay()
 *
 * Deprecated in drupal:11.4.0 and removed in drupal:12.0.0 (issue #3570839).
 *
 * @see https://www.drupal.org/project/drupal/issues/3570839
 */
final class ReplaceDeprecatedMediaLibraryFunctionsRector extends AbstractRector
{
    private const HOOKS_CLASS = 'Drupal\\media_library\\Hook\\MediaLibraryHooks';
    private const DISPLAY_MANAGER_CLASS = 'Drupal\\media_library\\MediaLibraryDisplayManager';

    /**
     * Map: old function name => [type, class FQCN, replacement method].
     *
     * type 'service' => rewrite to \Drupal::service(Class::class)->method()
     * type 'static'  => rewrite to Class::staticMethod()
     */
    private const FUNC_MAP = [
        '_media_library_views_form_media_library_after_build' => ['service', self::HOOKS_CLASS, 'viewsFormAfterBuild'],
        '_media_library_media_type_form_submit'               => ['service', self::HOOKS_CLASS, 'mediaTypeFormSubmit'],
        '_media_library_configure_form_display'               => ['static',  self::DISPLAY_MANAGER_CLASS, 'configureFormDisplay'],
        '_media_library_configure_view_display'               => ['static',  self::DISPLAY_MANAGER_CLASS, 'configureViewDisplay'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated media_library.module underscore functions with MediaLibraryHooks service calls or MediaLibraryDisplayManager static calls (removed in drupal:12.0.0)',
            [
                new CodeSample(
                    '_media_library_configure_form_display($type);',
                    '\\Drupal\\media_library\\MediaLibraryDisplayManager::configureFormDisplay($type);'
                ),
                new CodeSample(
                    '_media_library_configure_view_display($type);',
                    '\\Drupal\\media_library\\MediaLibraryDisplayManager::configureViewDisplay($type);'
                ),
                new CodeSample(
                    '_media_library_media_type_form_submit($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\media_library\\Hook\\MediaLibraryHooks::class)->mediaTypeFormSubmit($form, $form_state);'
                ),
                new CodeSample(
                    '_media_library_views_form_media_library_after_build($form, $form_state);',
                    '\\Drupal::service(\\Drupal\\media_library\\Hook\\MediaLibraryHooks::class)->viewsFormAfterBuild($form, $form_state);'
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

        $funcName = $node->name->toString();

        if (!isset(self::FUNC_MAP[$funcName])) {
            return null;
        }

        [$type, $className, $methodName] = self::FUNC_MAP[$funcName];

        if ($type === 'static') {
            // Rewrite to ClassName::staticMethod(...args...)
            return new StaticCall(
                new FullyQualified($className),
                $methodName,
                $node->args
            );
        }

        // type === 'service'
        // Rewrite to \Drupal::service(ClassName::class)->method(...args...)
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(
                new FullyQualified($className),
                'class'
            ))]
        );

        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
