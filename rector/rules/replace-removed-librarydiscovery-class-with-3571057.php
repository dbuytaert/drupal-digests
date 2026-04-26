<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571057
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites use Drupal\Core\Asset\LibraryDiscovery imports and all
// associated type-hint references to
// Drupal\Core\Asset\LibraryDiscoveryInterface. The LibraryDiscovery
// concrete class was deprecated in drupal:11.1.0 and removed in
// drupal:12.0.0 (issue #3571057, change record #3462970). The
// library.discovery service now injects a LibraryDiscoveryCollector, so
// consumers should type-hint against the interface.
//
// Before:
//   use Drupal\Core\Asset\LibraryDiscovery;
//   
//   class MyService {
//     public function __construct(
//       private LibraryDiscovery $libraryDiscovery,
//     ) {}
//   }
//
// After:
//   use Drupal\Core\Asset\LibraryDiscoveryInterface;
//   
//   class MyService {
//     public function __construct(
//       private \Drupal\Core\Asset\LibraryDiscoveryInterface $libraryDiscovery,
//     ) {}
//   }


use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the removed LibraryDiscovery class with LibraryDiscoveryInterface.
 *
 * Drupal\Core\Asset\LibraryDiscovery was deprecated in drupal:11.1.0 and
 * removed in drupal:12.0.0 (issue #3571057, change record #3462970). Consumer
 * code should use LibraryDiscoveryInterface instead. The concrete
 * implementation is LibraryDiscoveryCollector, injected via the
 * library.discovery service.
 *
 * @see https://www.drupal.org/node/3462970
 * @see https://www.drupal.org/project/drupal/issues/3571057
 */
final class ReplaceLibraryDiscoveryClassRector extends AbstractRector
{
    private const OLD_FQCN = 'Drupal\\Core\\Asset\\LibraryDiscovery';
    private const NEW_FQCN = 'Drupal\\Core\\Asset\\LibraryDiscoveryInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed Drupal\\Core\\Asset\\LibraryDiscovery with LibraryDiscoveryInterface (removed in drupal:12.0.0)',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Asset\LibraryDiscovery;

class MyService {
  public function __construct(
    private LibraryDiscovery $libraryDiscovery,
  ) {}
}
CODE,
                    <<<'CODE'
use Drupal\Core\Asset\LibraryDiscoveryInterface;

class MyService {
  public function __construct(
    private \Drupal\Core\Asset\LibraryDiscoveryInterface $libraryDiscovery,
  ) {}
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Name::class, Use_::class];
    }

    public function refactor(Node $node): ?Node
    {
        // Handle: use Drupal\Core\Asset\LibraryDiscovery;
        if ($node instanceof Use_) {
            $changed = false;
            foreach ($node->uses as $use) {
                if ($use instanceof UseUse
                    && $use->name->toString() === self::OLD_FQCN
                ) {
                    $use->name = new Name(self::NEW_FQCN);
                    $changed = true;
                }
            }
            return $changed ? $node : null;
        }

        // Handle Name nodes: type hints and fully-qualified references.
        if ($node instanceof Name) {
            $name = $node->toString();

            // Match both short form (imported via use) and fully-qualified.
            if ($name !== self::OLD_FQCN && $name !== 'LibraryDiscovery') {
                return null;
            }

            // Honour the resolver: skip if the name resolves to something else.
            $resolved = $node->getAttribute('resolvedName');
            if ($resolved !== null && $resolved->toString() !== self::OLD_FQCN) {
                return null;
            }

            if ($node instanceof FullyQualified) {
                return new FullyQualified(self::NEW_FQCN, $node->getAttributes());
            }

            // Short form: return the short name of the new interface.
            return new Name('LibraryDiscoveryInterface', $node->getAttributes());
        }

        return null;
    }
}
