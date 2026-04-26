<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3495600
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the four deprecated global PHP constants
// JSONAPI_FILTER_AMONG_ALL, JSONAPI_FILTER_AMONG_PUBLISHED,
// JSONAPI_FILTER_AMONG_ENABLED, and JSONAPI_FILTER_AMONG_OWN with their
// equivalents on the new \Drupal\jsonapi\JsonApiFilter class. These
// constants were deprecated in Drupal 11.3 and will be removed in 13.0,
// to allow the jsonapi.module file to be deleted.
//
// Before:
//   return [
//     JSONAPI_FILTER_AMONG_ALL => AccessResult::allowedIfHasPermission($account, 'administer llamas'),
//     JSONAPI_FILTER_AMONG_PUBLISHED => AccessResult::allowedIfHasPermission($account, 'view published llamas'),
//     JSONAPI_FILTER_AMONG_ENABLED => AccessResult::allowedIfHasPermission($account, 'view enabled llamas'),
//     JSONAPI_FILTER_AMONG_OWN => AccessResult::allowed(),
//   ];
//
// After:
//   return [
//     \Drupal\jsonapi\JsonApiFilter::AMONG_ALL => AccessResult::allowedIfHasPermission($account, 'administer llamas'),
//     \Drupal\jsonapi\JsonApiFilter::AMONG_PUBLISHED => AccessResult::allowedIfHasPermission($account, 'view published llamas'),
//     \Drupal\jsonapi\JsonApiFilter::AMONG_ENABLED => AccessResult::allowedIfHasPermission($account, 'view enabled llamas'),
//     \Drupal\jsonapi\JsonApiFilter::AMONG_OWN => AccessResult::allowed(),
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated JSONAPI_FILTER_AMONG_* global constants with
 * \Drupal\jsonapi\JsonApiFilter::AMONG_* class constants.
 */
final class ReplaceJsonApiFilterConstantsRector extends AbstractRector
{
    /**
     * Map of deprecated global constant name => JsonApiFilter class constant name.
     */
    private const CONSTANT_MAP = [
        'JSONAPI_FILTER_AMONG_ALL'       => 'AMONG_ALL',
        'JSONAPI_FILTER_AMONG_PUBLISHED' => 'AMONG_PUBLISHED',
        'JSONAPI_FILTER_AMONG_ENABLED'   => 'AMONG_ENABLED',
        'JSONAPI_FILTER_AMONG_OWN'       => 'AMONG_OWN',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated JSONAPI_FILTER_AMONG_* global constants with \Drupal\jsonapi\JsonApiFilter::AMONG_* class constants.',
            [
                new CodeSample(
                    'return [JSONAPI_FILTER_AMONG_ALL => AccessResult::allowed()];',
                    'return [\Drupal\jsonapi\JsonApiFilter::AMONG_ALL => AccessResult::allowed()];'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /** @param ConstFetch $node */
    public function refactor(Node $node): ?Node
    {
        $constName = $this->getName($node);
        if ($constName === null || !isset(self::CONSTANT_MAP[$constName])) {
            return null;
        }

        $classConst = self::CONSTANT_MAP[$constName];

        return new ClassConstFetch(
            new FullyQualified('Drupal\jsonapi\JsonApiFilter'),
            $classConst
        );
    }
}
