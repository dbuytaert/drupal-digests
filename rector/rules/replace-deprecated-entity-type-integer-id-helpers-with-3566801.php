<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3566801
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4 deprecated three protected helper methods used to test
// whether an entity type has an integer ID key:
// DefaultHtmlRouteProvider::getEntityTypeIdKeyType() (used in ===
// 'integer' comparisons), CommentTypeForm::entityTypeSupportsComments(),
// and OverridesSectionStorage::hasIntegerId($entityType). All three are
// removed in Drupal 13.0. The replacement is the new
// EntityTypeInterface::hasIntegerId() method called directly on the
// entity type object.
//
// Before:
//   // In a DefaultHtmlRouteProvider subclass
//   if ($this->getEntityTypeIdKeyType($entity_type) === 'integer') { /* … */ }
//   
//   // In a CommentTypeForm subclass
//   $result = $this->entityTypeSupportsComments($entity_type);
//   
//   // In an OverridesSectionStorage subclass
//   $result = $this->hasIntegerId($entity_type);
//
// After:
//   // All three patterns become:
//   if ($entity_type->hasIntegerId()) { /* … */ }
//   
//   $result = $entity_type->hasIntegerId();
//   
//   $result = $entity_type->hasIntegerId();


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces three deprecated protected helper methods for checking integer entity IDs.
 *
 * Deprecated in drupal:11.4.0, removed in drupal:13.0.0.
 * See https://www.drupal.org/node/3566814
 *
 * Handles:
 *   - $this->getEntityTypeIdKeyType($entityType) === 'integer'
 *     (DefaultHtmlRouteProvider subclasses)
 *   - $this->entityTypeSupportsComments($entityType)
 *     (CommentTypeForm subclasses)
 *   - $this->hasIntegerId($entityType)
 *     (OverridesSectionStorage subclasses)
 *
 * All three become $entityType->hasIntegerId().
 */
final class UseEntityTypeHasIntegerIdRector extends AbstractRector
{
    private const SIMPLE_METHOD_NAMES = [
        'entityTypeSupportsComments',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated entity-type integer-ID helper methods with EntityTypeInterface::hasIntegerId()',
            [
                new CodeSample(
                    '$this->getEntityTypeIdKeyType($entity_type) === \'integer\'',
                    '$entity_type->hasIntegerId()'
                ),
                new CodeSample(
                    '$this->entityTypeSupportsComments($entity_type)',
                    '$entity_type->hasIntegerId()'
                ),
                new CodeSample(
                    '$this->hasIntegerId($entity_type)',
                    '$entity_type->hasIntegerId()'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Identical::class, MethodCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Identical) {
            return $this->refactorIdentical($node);
        }

        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        return null;
    }

    private function refactorIdentical(Identical $node): ?Node
    {
        [$methodCall, $string] = $this->extractMethodAndString($node);

        if ($methodCall === null || $string === null) {
            return null;
        }

        if (!$this->isThisGetEntityTypeIdKeyType($methodCall)) {
            return null;
        }

        if ($string->value !== 'integer') {
            return null;
        }

        if (count($methodCall->args) !== 1) {
            return null;
        }

        return new MethodCall($methodCall->args[0]->value, 'hasIntegerId');
    }

    private function refactorMethodCall(MethodCall $node): ?Node
    {
        if (!($node->var instanceof Variable) || $node->var->name !== 'this') {
            return null;
        }

        $methodName = $this->getName($node->name);
        if ($methodName === null) {
            return null;
        }

        if (in_array($methodName, self::SIMPLE_METHOD_NAMES, true)
            && count($node->args) === 1
        ) {
            return new MethodCall($node->args[0]->value, 'hasIntegerId');
        }

        if ($methodName === 'hasIntegerId' && count($node->args) === 1) {
            return new MethodCall($node->args[0]->value, 'hasIntegerId');
        }

        return null;
    }

    /**
     * @return array{MethodCall|null, String_|null}
     */
    private function extractMethodAndString(Identical $node): array
    {
        if ($node->left instanceof MethodCall && $node->right instanceof String_) {
            return [$node->left, $node->right];
        }
        if ($node->right instanceof MethodCall && $node->left instanceof String_) {
            return [$node->right, $node->left];
        }
        return [null, null];
    }

    private function isThisGetEntityTypeIdKeyType(MethodCall $node): bool
    {
        if (!($node->var instanceof Variable) || $node->var->name !== 'this') {
            return false;
        }
        return $this->getName($node->name) === 'getEntityTypeIdKeyType';
    }
}
