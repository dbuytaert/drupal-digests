<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3069442
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated views_entity_field_label() function
// (deprecated in Drupal 11.2, removed in 12.0) with the equivalent
// \Drupal::service('entity_field.manager')->getFieldLabels(). The new
// method lives on EntityFieldManagerInterface and is accessible both as
// a service call and via dependency injection, making it usable outside
// of Views contexts.
//
// Before:
//   [$label, $all_labels] = views_entity_field_label($entity_type_id, $field_name);
//
// After:
//   [$label, $all_labels] = \Drupal::service('entity_field.manager')->getFieldLabels($entity_type_id, $field_name);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated views_entity_field_label() with
 * \Drupal::service('entity_field.manager')->getFieldLabels().
 */
final class ViewsEntityFieldLabelRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated views_entity_field_label() with \\Drupal::service('entity_field.manager')->getFieldLabels()",
            [
                new CodeSample(
                    '[$label, $all_labels] = views_entity_field_label($entity_type_id, $field_name);',
                    "[$label, $all_labels] = \\Drupal::service('entity_field.manager')->getFieldLabels(\$entity_type_id, \$field_name);"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'views_entity_field_label')) {
            return null;
        }

        // Build \Drupal::service('entity_field.manager')
        $serviceCall = $this->nodeFactory->createStaticCall(
            'Drupal',
            'service',
            [$this->nodeFactory->createArg(new String_('entity_field.manager'))]
        );

        // Build ->getFieldLabels($entity_type, $field_name)
        return $this->nodeFactory->createMethodCall($serviceCall, 'getFieldLabels', $node->args);
    }
}
