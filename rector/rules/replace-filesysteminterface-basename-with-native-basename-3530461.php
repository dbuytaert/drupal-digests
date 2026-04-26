<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3530461
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to FileSystemInterface::basename() and
// FileSystem::basename() with the PHP native basename() function. The
// method is deprecated in drupal:11.3.0 and removed in drupal:13.0.0.
// The native function behaves identically on PHP 8.x+, eliminating a
// dependency on the file_system service and any risk of circular
// container references.
//
// Before:
//   $fileSystem->basename($uri, '.txt');
//
// After:
//   basename($uri, '.txt');


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites $fileSystem->basename($uri, $suffix) to basename($uri, $suffix).
 *
 * FileSystemInterface::basename() is deprecated in drupal:11.3.0 and removed
 * in drupal:13.0.0. The replacement is PHP native basename(), which works
 * identically on PHP 8.x+.
 */
final class FileSystemBasenameToNativeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated FileSystemInterface::basename() calls with PHP native basename()',
            [
                new CodeSample(
                    '$fileSystem->basename($uri, $suffix);',
                    'basename($uri, $suffix);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        // Only handle calls named 'basename'.
        if (!$this->isName($node->name, 'basename')) {
            return null;
        }

        // Check that the caller is typed as FileSystemInterface or FileSystem.
        $callerType = $this->getType($node->var);
        $isFileSystem = false;
        foreach ([
            'Drupal\Core\File\FileSystemInterface',
            'Drupal\Core\File\FileSystem',
        ] as $class) {
            if ($callerType->isSuperTypeOf(
                new \PHPStan\Type\ObjectType($class)
            )->yes()) {
                $isFileSystem = true;
                break;
            }
        }

        if (!$isFileSystem) {
            return null;
        }

        // Build a native basename($uri) or basename($uri, $suffix) call.
        return new FuncCall(new Name('basename'), $node->getArgs());
    }
}
