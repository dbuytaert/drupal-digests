<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3551446
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.3 the workspaces.association service and
// WorkspaceAssociationInterface were renamed to workspaces.tracker and
// WorkspaceTrackerInterface to align naming with the "tracked entities"
// terminology used throughout the module. This rule updates use imports,
// type hints, and \Drupal::service() string arguments to the new
// identifiers.
//
// Before:
//   use Drupal\workspaces\WorkspaceAssociationInterface;
//   
//   class MyClass {
//       public function __construct(
//           protected WorkspaceAssociationInterface $association,
//       ) {}
//   
//       public function load(): void {
//           $svc = \Drupal::service('workspaces.association');
//       }
//   }
//
// After:
//   use Drupal\workspaces\WorkspaceTrackerInterface;
//   
//   class MyClass {
//       public function __construct(
//           protected WorkspaceTrackerInterface $association,
//       ) {}
//   
//       public function load(): void {
//           $svc = \Drupal::service('workspaces.tracker');
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated workspaces.association service ID and
 * WorkspaceAssociationInterface with workspaces.tracker /
 * WorkspaceTrackerInterface introduced in Drupal 11.3.
 */
final class WorkspacesAssociationToTrackerRector extends AbstractRector
{
    private const OLD_CLASS = 'Drupal\\workspaces\\WorkspaceAssociationInterface';
    private const NEW_CLASS = 'Drupal\\workspaces\\WorkspaceTrackerInterface';
    private const NEW_PARTS = ['Drupal', 'workspaces', 'WorkspaceTrackerInterface'];
    private const OLD_SERVICE = 'workspaces.association';
    private const NEW_SERVICE = 'workspaces.tracker';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated workspaces.association service and WorkspaceAssociationInterface with workspaces.tracker and WorkspaceTrackerInterface',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Drupal\workspaces\WorkspaceAssociationInterface;

class MyClass {
    public function __construct(
        protected WorkspaceAssociationInterface $association,
    ) {}

    public function load(): void {
        $svc = \Drupal::service('workspaces.association');
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Drupal\workspaces\WorkspaceTrackerInterface;

class MyClass {
    public function __construct(
        protected WorkspaceTrackerInterface $association,
    ) {}

    public function load(): void {
        $svc = \Drupal::service('workspaces.tracker');
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [String_::class, Name::class, FullyQualified::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Replace service ID string.
        if ($node instanceof String_) {
            if ($node->value === self::OLD_SERVICE) {
                return new String_(self::NEW_SERVICE);
            }
            return null;
        }

        // Both Name (use statements) and FullyQualified (resolved type hints).
        if ($node instanceof Name) {
            if ($node->toString() !== self::OLD_CLASS) {
                return null;
            }

            if ($node instanceof FullyQualified) {
                // Type hint resolved to FQCN: replace with short unqualified name
                // so the printer uses the updated use statement.
                return new Name('WorkspaceTrackerInterface');
            }

            // Plain Name (use-statement node): update to the new FQCN path.
            return new Name(self::NEW_PARTS);
        }

        return null;
    }
}
