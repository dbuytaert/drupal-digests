<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3574727
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces language_configuration_element_submit() with the static
// method \Drupal\language\Element\LanguageConfiguration::submit(), and
// language_process_language_select() with
// \Drupal::service(LanguageHooks::class)->processLanguageSelect(). Both
// procedural functions were deprecated in drupal:11.4.0 (removed in
// drupal:12.0.0) as part of moving language module logic into proper OOP
// classes.
//
// Before:
//   language_configuration_element_submit($form, $form_state);
//   language_process_language_select($element);
//
// After:
//   \Drupal\language\Element\LanguageConfiguration::submit($form, $form_state);
//   \Drupal::service(\Drupal\language\Hook\LanguageHooks::class)->processLanguageSelect($element);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LanguageModuleFunctionDeprecationsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated language module procedural functions with their OOP replacements',
            [
                new CodeSample(
                    'language_configuration_element_submit($form, $form_state);',
                    '\Drupal\language\Element\LanguageConfiguration::submit($form, $form_state);'
                ),
                new CodeSample(
                    'language_process_language_select($element);',
                    '\Drupal::service(\Drupal\language\Hook\LanguageHooks::class)->processLanguageSelect($element);'
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
        if ($this->isName($node, 'language_configuration_element_submit')) {
            return $this->nodeFactory->createStaticCall(
                'Drupal\language\Element\LanguageConfiguration',
                'submit',
                $node->args
            );
        }

        if ($this->isName($node, 'language_process_language_select')) {
            $serviceCall = $this->nodeFactory->createStaticCall('Drupal', 'service', [
                $this->nodeFactory->createClassConstReference('Drupal\language\Hook\LanguageHooks'),
            ]);
            return $this->nodeFactory->createMethodCall($serviceCall, 'processLanguageSelect', $node->args);
        }

        return null;
    }
}
