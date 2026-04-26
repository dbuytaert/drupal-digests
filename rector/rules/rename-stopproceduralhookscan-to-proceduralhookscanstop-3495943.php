<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3495943
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 (issue #3495943) renamed the PHP attribute class
// Drupal\Core\Hook\Attribute\StopProceduralHookScan to
// ProceduralHookScanStop. The rename was required to keep the attribute
// name consistent with the new skip_procedural_hook_scan container
// parameter. This rule updates both the use import and every
// #[StopProceduralHookScan] attribute reference in a file.
//
// Before:
//   use Drupal\Core\Hook\Attribute\StopProceduralHookScan;
//   
//   #[StopProceduralHookScan]
//   function mymodule_helper(): void {}
//
// After:
//   use Drupal\Core\Hook\Attribute\ProceduralHookScanStop;
//   
//   #[ProceduralHookScanStop]
//   function mymodule_helper(): void {}


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\UseUse;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames #[StopProceduralHookScan] to #[ProceduralHookScanStop].
 *
 * Drupal 11.2 renamed the PHP attribute class
 * Drupal\Core\Hook\Attribute\StopProceduralHookScan
 * to ProceduralHookScanStop (issue #3495943). This rule updates both
 * the use statement and every attribute reference in a file.
 */
final class RenameStopProceduralHookScanRector extends AbstractRector
{
    private const OLD_FQCN = 'Drupal\\Core\\Hook\\Attribute\\StopProceduralHookScan';
    private const NEW_FQCN = 'Drupal\\Core\\Hook\\Attribute\\ProceduralHookScanStop';
    private const NEW_SHORT = 'ProceduralHookScanStop';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename #[StopProceduralHookScan] attribute to #[ProceduralHookScanStop] and update its use statement',
            [
                new CodeSample(
                    "use Drupal\\Core\\Hook\\Attribute\\StopProceduralHookScan;\n\n#[StopProceduralHookScan]\nfunction mymodule_helper(): void {}",
                    "use Drupal\\Core\\Hook\\Attribute\\ProceduralHookScanStop;\n\n#[ProceduralHookScanStop]\nfunction mymodule_helper(): void {}"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [UseUse::class, Attribute::class];
    }

    /** @param UseUse|Attribute $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof UseUse) {
            if ($node->name->toString() === self::OLD_FQCN) {
                $node->name = new Name(explode('\\', self::NEW_FQCN));
                return $node;
            }
            return null;
        }

        // Rector resolves attribute names to FullyQualified before our rule runs.
        if ($node instanceof Attribute) {
            if ($node->name instanceof FullyQualified && $node->name->toString() === self::OLD_FQCN) {
                // Use the short name; the updated use statement will import it.
                $node->name = new Name(self::NEW_SHORT);
                return $node;
            }
        }

        return null;
    }
}
