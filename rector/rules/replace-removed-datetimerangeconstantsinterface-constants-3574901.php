<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3574901
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the three DateTimeRangeConstantsInterface string constants
// (BOTH, START_DATE, END_DATE) with their DateTimeRangeDisplayOptions
// backed-enum equivalents (::Both->value, ::StartDate->value,
// ::EndDate->value), and rewrites calls to the removed
// datetime_type_field_views_data_helper() procedural function with
// \Drupal::service('datetime.views_helper')->buildViewsData(). Both APIs
// were deprecated in drupal:11.2.0 and removed in drupal:12.0.0 (issue
// #3574901).
//
// Before:
//   use Drupal\datetime_range\DateTimeRangeConstantsInterface;
//   
//   $fromTo = DateTimeRangeConstantsInterface::BOTH;
//   $fromTo = DateTimeRangeConstantsInterface::START_DATE;
//   $fromTo = DateTimeRangeConstantsInterface::END_DATE;
//   
//   datetime_type_field_views_data_helper($field_storage, $data, 'value');
//
// After:
//   use Drupal\datetime_range\DateTimeRangeDisplayOptions;
//   
//   $fromTo = DateTimeRangeDisplayOptions::Both->value;
//   $fromTo = DateTimeRangeDisplayOptions::StartDate->value;
//   $fromTo = DateTimeRangeDisplayOptions::EndDate->value;
//   
//   \Drupal::service('datetime.views_helper')->buildViewsData($field_storage, $data, 'value');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces removed datetime/datetime_range deprecated APIs with their successors.
 *
 * Two APIs were deprecated in drupal:11.2.0 and removed in drupal:12.0.0
 * (issue #3574901):
 *
 * 1. The procedural function datetime_type_field_views_data_helper() is
 *    replaced by \Drupal::service('datetime.views_helper')->buildViewsData().
 *
 * 2. The DateTimeRangeConstantsInterface string constants BOTH, START_DATE,
 *    END_DATE are replaced by the DateTimeRangeDisplayOptions backed-enum
 *    cases (::Both->value, ::StartDate->value, ::EndDate->value).
 */
final class ReplaceDatetimeDeprecatedApisRector extends AbstractRector
{
    private const CONSTANTS_INTERFACE = 'Drupal\\datetime_range\\DateTimeRangeConstantsInterface';
    private const DISPLAY_OPTIONS_ENUM = 'Drupal\\datetime_range\\DateTimeRangeDisplayOptions';

    /** Map: interface constant name => enum case name */
    private const CONST_MAP = [
        'BOTH'       => 'Both',
        'START_DATE' => 'StartDate',
        'END_DATE'   => 'EndDate',
    ];

    private const HELPER_FUNC = 'datetime_type_field_views_data_helper';
    private const SERVICE_NAME = 'datetime.views_helper';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed DateTimeRangeConstantsInterface constants and datetime_type_field_views_data_helper() with their drupal:12 equivalents',
            [
                new CodeSample(
                    'DateTimeRangeConstantsInterface::BOTH;',
                    '\\Drupal\\datetime_range\\DateTimeRangeDisplayOptions::Both->value;'
                ),
                new CodeSample(
                    "datetime_type_field_views_data_helper(\$field_storage, \$data, \$column);",
                    "\\Drupal::service('datetime.views_helper')->buildViewsData(\$field_storage, \$data, \$column);"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class, FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassConstFetch) {
            return $this->refactorClassConst($node);
        }

        if ($node instanceof FuncCall) {
            return $this->refactorFuncCall($node);
        }

        return null;
    }

    private function refactorClassConst(ClassConstFetch $node): ?Node
    {
        if (!$node->name instanceof Identifier) {
            return null;
        }

        $constName = $node->name->toString();

        if (!isset(self::CONST_MAP[$constName])) {
            return null;
        }

        if (!$this->isName($node->class, self::CONSTANTS_INTERFACE)) {
            return null;
        }

        $caseName = self::CONST_MAP[$constName];

        // Build: \Drupal\datetime_range\DateTimeRangeDisplayOptions::CaseName
        $enumCaseFetch = new ClassConstFetch(
            new FullyQualified(self::DISPLAY_OPTIONS_ENUM),
            $caseName
        );

        // Build: ->value
        return new PropertyFetch($enumCaseFetch, 'value');
    }

    private function refactorFuncCall(FuncCall $node): ?Node
    {
        if (!$node->name instanceof Name) {
            return null;
        }

        if ($node->name->toString() !== self::HELPER_FUNC) {
            return null;
        }

        // datetime_type_field_views_data_helper($field_storage, $data, $column_name)
        // => \Drupal::service('datetime.views_helper')->buildViewsData($field_storage, $data, $column_name)

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_(self::SERVICE_NAME))]
        );

        return new MethodCall(
            $serviceCall,
            'buildViewsData',
            $node->args
        );
    }
}
