<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3560398
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites calls to the procedural functions _dblog_get_message_types()
// and dblog_filters(), both deprecated in drupal:11.4.0 and removed in
// drupal:13.0.0, to equivalent calls on the \Drupal\dblog\DbLogFilters
// service. This avoids triggering E_USER_DEPRECATED errors and aligns
// code with the object-oriented service-based pattern introduced for the
// dblog module.
//
// Before:
//   $types = _dblog_get_message_types();
//   $filters = dblog_filters();
//
// After:
//   $types = \Drupal::service(\Drupal\dblog\DbLogFilters::class)->getMessageTypes();
//   $filters = \Drupal::service(\Drupal\dblog\DbLogFilters::class)->filters();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;

/**
 * Replaces deprecated _dblog_get_message_types() and dblog_filters() with
 * calls to the \Drupal\dblog\DbLogFilters service introduced in Drupal 11.4.0.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:13.0.0.
 * @see https://www.drupal.org/node/3560399
 */
final class ReplaceDblogProceduralFunctionsRector extends AbstractRector
{
    /**
     * Maps each deprecated procedural function to its DbLogFilters method.
     */
    private const FUNCTION_MAP = [
        '_dblog_get_message_types' => 'getMessageTypes',
        'dblog_filters'            => 'filters',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated _dblog_get_message_types() and dblog_filters() procedural functions with \Drupal\dblog\DbLogFilters service method calls.',
            [
                new CodeSample(
                    '_dblog_get_message_types();',
                    '\Drupal::service(\Drupal\dblog\DbLogFilters::class)->getMessageTypes();'
                ),
                new CodeSample(
                    'dblog_filters();',
                    '\Drupal::service(\Drupal\dblog\DbLogFilters::class)->filters();'
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

        if (!array_key_exists($funcName, self::FUNCTION_MAP)) {
            return null;
        }

        $serviceMethod = self::FUNCTION_MAP[$funcName];

        // Build: \Drupal::service(\Drupal\dblog\DbLogFilters::class)
        $serviceCall = new Node\Expr\StaticCall(
            new Node\Name\FullyQualified('Drupal'),
            'service',
            [new Node\Arg(
                new Node\Expr\ClassConstFetch(
                    new Node\Name\FullyQualified('Drupal\dblog\DbLogFilters'),
                    'class'
                )
            )]
        );

        // Build: ->getMessageTypes() or ->filters()
        return new Node\Expr\MethodCall($serviceCall, $serviceMethod);
    }
}

return static function (RectorConfig $config): void {
    $config->rules([ReplaceDblogProceduralFunctionsRector::class]);
};
