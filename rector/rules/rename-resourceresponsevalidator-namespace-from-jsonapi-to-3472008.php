<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3472008
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal core issue #3472008, ResourceResponseValidator was moved
// from the jsonapi module into a new jsonapi_response_validator
// submodule. Any code importing
// Drupal\jsonapi\EventSubscriber\ResourceResponseValidator must be
// updated to Drupal\jsonapi_response_validator\EventSubscriber\ResourceR
// esponseValidator. This rule rewrites the use import statement; the
// short class name in the body of the file remains valid after the
// rename.
//
// Before:
//   use Drupal\jsonapi\EventSubscriber\ResourceResponseValidator;
//
// After:
//   use Drupal\jsonapi_response_validator\EventSubscriber\ResourceResponseValidator;


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\UseUse;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames the namespace of ResourceResponseValidator from the jsonapi module
 * to the new jsonapi_response_validator submodule.
 *
 * The class moved from Drupal\jsonapi\EventSubscriber\ResourceResponseValidator
 * to Drupal\jsonapi_response_validator\EventSubscriber\ResourceResponseValidator
 * in Drupal core issue #3472008.
 */
final class RenameResourceResponseValidatorRector extends AbstractRector
{
    private const OLD_CLASS = 'Drupal\\jsonapi\\EventSubscriber\\ResourceResponseValidator';
    private const NEW_CLASS = 'Drupal\\jsonapi_response_validator\\EventSubscriber\\ResourceResponseValidator';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename ResourceResponseValidator from Drupal\\jsonapi\\EventSubscriber to Drupal\\jsonapi_response_validator\\EventSubscriber',
            [
                new CodeSample(
                    'use Drupal\\jsonapi\\EventSubscriber\\ResourceResponseValidator;',
                    'use Drupal\\jsonapi_response_validator\\EventSubscriber\\ResourceResponseValidator;'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [UseUse::class];
    }

    /** @param UseUse $node */
    public function refactor(Node $node): ?Node
    {
        if ($node->name->toString() !== self::OLD_CLASS) {
            return null;
        }

        $node->name = new Name(explode('\\\\', self::NEW_CLASS));
        return $node;
    }
}
