<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3092001
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites new NodePermissions() (no arguments) to new
// NodePermissions(\Drupal::entityTypeManager()). In drupal:11.2.0,
// NodePermissions gained a constructor that accepts
// EntityTypeManagerInterface; calling it without the argument triggers a
// deprecation. The argument becomes mandatory in drupal:12.0.0. See
// https://www.drupal.org/node/3515921.
//
// Before:
//   new \Drupal\node\NodePermissions()
//
// After:
//   new \Drupal\node\NodePermissions(\Drupal::entityTypeManager())


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites `new NodePermissions()` to pass the entity type manager argument.
 *
 * In drupal:11.2.0, NodePermissions gained a constructor that requires
 * EntityTypeManagerInterface. Calling it without the argument triggers a
 * deprecation; drupal:12.0.0 will make the argument mandatory.
 */
final class PassEntityTypeManagerToNodePermissionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Pass EntityTypeManagerInterface to NodePermissions constructor (deprecated in drupal:11.2.0, required in drupal:12.0.0)',
            [
                new CodeSample(
                    'new \\Drupal\\node\\NodePermissions()',
                    'new \\Drupal\\node\\NodePermissions(\\Drupal::entityTypeManager())'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->class instanceof \PhpParser\Node\Name) {
            return null;
        }

        if (!$this->isName($node->class, 'Drupal\\node\\NodePermissions')) {
            return null;
        }

        // Only rewrite when called with no arguments (the deprecated form).
        if ($node->args !== []) {
            return null;
        }

        // Build \Drupal::entityTypeManager() static call.
        $entityTypeManagerCall = $this->nodeFactory->createStaticCall(
            'Drupal',
            'entityTypeManager'
        );

        $node->args = [$this->nodeFactory->createArg($entityTypeManagerCall)];

        return $node;
    }
}
