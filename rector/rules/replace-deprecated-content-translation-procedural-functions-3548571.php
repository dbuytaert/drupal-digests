<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3548571
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces six procedural functions deprecated in Drupal 11.4.0 that
// originated in content_translation.admin.inc. Each call is rewritten to
// the equivalent \Drupal::service() call:
// content_translation_translate_access() becomes
// content_translation.manager->access(), and the form element helpers
// (content_translation_enable_widget, *_process, *_validate, *_submit,
// _content_translation_install_field_storage_definitions) become calls
// on ContentTranslationEnableTranslationPerBundle or
// ContentTranslationHooks services.
//
// Before:
//   content_translation_translate_access($entity);
//   content_translation_enable_widget($entity_type, $bundle, $form, $form_state);
//   content_translation_language_configuration_element_process($element, $form_state, $form);
//   content_translation_language_configuration_element_validate($element, $form_state, $form);
//   content_translation_language_configuration_element_submit($form, $form_state);
//   _content_translation_install_field_storage_definitions($entity_type_id);
//
// After:
//   \Drupal::service('content_translation.manager')->access($entity);
//   \Drupal::service(\Drupal\content_translation\ContentTranslationEnableTranslationPerBundle::class)->getWidget($entity_type, $bundle, $form, $form_state);
//   \Drupal::service(\Drupal\content_translation\ContentTranslationEnableTranslationPerBundle::class)->configElementProcess($element, $form_state, $form);
//   \Drupal::service(\Drupal\content_translation\ContentTranslationEnableTranslationPerBundle::class)->configElementValidate($element, $form_state, $form);
//   \Drupal::service(\Drupal\content_translation\ContentTranslationEnableTranslationPerBundle::class)->configElementSubmit($form, $form_state);
//   \Drupal::service(\Drupal\content_translation\Hook\ContentTranslationHooks::class)->installFieldStorageDefinitions($entity_type_id);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated content_translation procedural functions from
 * content_translation.admin.inc with their service-based equivalents
 * introduced in Drupal 11.4.
 */
final class ContentTranslationAdminFunctionsRector extends AbstractRector
{
    private const FUNCTION_MAP = [
        'content_translation_translate_access' => [
            'service' => 'content_translation.manager',
            'method' => 'access',
            'use_class_const' => false,
        ],
        'content_translation_enable_widget' => [
            'service' => 'Drupal\\content_translation\\ContentTranslationEnableTranslationPerBundle',
            'method' => 'getWidget',
            'use_class_const' => true,
        ],
        'content_translation_language_configuration_element_process' => [
            'service' => 'Drupal\\content_translation\\ContentTranslationEnableTranslationPerBundle',
            'method' => 'configElementProcess',
            'use_class_const' => true,
        ],
        'content_translation_language_configuration_element_validate' => [
            'service' => 'Drupal\\content_translation\\ContentTranslationEnableTranslationPerBundle',
            'method' => 'configElementValidate',
            'use_class_const' => true,
        ],
        'content_translation_language_configuration_element_submit' => [
            'service' => 'Drupal\\content_translation\\ContentTranslationEnableTranslationPerBundle',
            'method' => 'configElementSubmit',
            'use_class_const' => true,
        ],
        '_content_translation_install_field_storage_definitions' => [
            'service' => 'Drupal\\content_translation\\Hook\\ContentTranslationHooks',
            'method' => 'installFieldStorageDefinitions',
            'use_class_const' => true,
        ],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated content_translation procedural functions with service-based equivalents (Drupal 11.4+).',
            [
                new CodeSample(
                    'content_translation_translate_access($entity);',
                    "\\Drupal::service('content_translation.manager')->access(\$entity);"
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
        if (!$node->name instanceof Name) {
            return null;
        }

        $funcName = $node->name->toString();

        if (!array_key_exists($funcName, self::FUNCTION_MAP)) {
            return null;
        }

        $mapping = self::FUNCTION_MAP[$funcName];
        $serviceId = $mapping['service'];
        $methodName = $mapping['method'];
        $useClassConst = $mapping['use_class_const'];

        $drupalClass = new FullyQualified('Drupal');
        if ($useClassConst) {
            $serviceArg = new Arg(
                new ClassConstFetch(
                    new FullyQualified($serviceId),
                    'class'
                )
            );
        } else {
            $serviceArg = new Arg(new String_($serviceId));
        }

        $serviceCall = new StaticCall(
            $drupalClass,
            'service',
            [$serviceArg]
        );

        return new MethodCall(
            $serviceCall,
            $methodName,
            $node->args
        );
    }
}
