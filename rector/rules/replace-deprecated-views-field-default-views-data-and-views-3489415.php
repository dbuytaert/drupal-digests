<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3489415
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecated views_field_default_views_data() and
// _views_field_get_entity_type_storage() in favour of the
// views.field_data_provider service. Calls to these procedural helpers
// must be replaced with \Drupal::service('views.field_data_provider')-
// >defaultFieldImplementation() and ->getSqlStorageForField()
// respectively before Drupal 12. Contrib implementations of
// hook_field_views_data() are the primary consumer.
//
// Before:
//   views_field_default_views_data($field_storage);
//
// After:
//   \Drupal::service('views.field_data_provider')->defaultFieldImplementation($field_storage);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated views_field_default_views_data() and
 * _views_field_get_entity_type_storage() with service calls on
 * views.field_data_provider.
 */
final class ViewsFieldDefaultViewsDataRector extends AbstractRector
{
    private const MAP = [
        'views_field_default_views_data'       => 'defaultFieldImplementation',
        '_views_field_get_entity_type_storage' => 'getSqlStorageForField',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated views_field_default_views_data() and _views_field_get_entity_type_storage() with calls on the views.field_data_provider service.',
            [
                new CodeSample(
                    'views_field_default_views_data($field_storage);',
                    "\\Drupal::service('views.field_data_provider')->defaultFieldImplementation(\$field_storage);"
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
        $name = $this->getName($node->name);
        if ($name === null || !isset(self::MAP[$name])) {
            return null;
        }

        $methodName = self::MAP[$name];

        // Build \Drupal::service('views.field_data_provider')
        $serviceCall = new StaticCall(
            new Name\FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('views.field_data_provider'))]
        );

        // Build ->defaultFieldImplementation(...) or ->getSqlStorageForField(...)
        return new MethodCall(
            $serviceCall,
            $methodName,
            $node->args
        );
    }
}
