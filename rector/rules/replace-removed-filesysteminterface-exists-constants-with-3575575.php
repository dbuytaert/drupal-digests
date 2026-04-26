<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3575575
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the three integer constants
// FileSystemInterface::EXISTS_RENAME, EXISTS_REPLACE, and EXISTS_ERROR
// to their \Drupal\Core\File\FileExists enum equivalents (Rename,
// Replace, Error). These constants were deprecated in drupal:10.3.0 and
// removed in drupal:12.0.0 (issue #3575575); passing them to
// FileSystem::copy(), ::move(), ::saveData(), or
// ::getDestinationFilename() now causes a TypeError at runtime.
//
// Before:
//   $fileSystem->copy($src, $dst, FileSystemInterface::EXISTS_RENAME);
//   $fileSystem->move($src, $dst, FileSystemInterface::EXISTS_REPLACE);
//   $fileSystem->saveData($data, $dst, FileSystemInterface::EXISTS_ERROR);
//
// After:
//   $fileSystem->copy($src, $dst, \Drupal\Core\File\FileExists::Rename);
//   $fileSystem->move($src, $dst, \Drupal\Core\File\FileExists::Replace);
//   $fileSystem->saveData($data, $dst, \Drupal\Core\File\FileExists::Error);


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces removed FileSystemInterface::EXISTS_* integer constants with the
 * FileExists enum cases introduced in Drupal 10.3.0.
 *
 * FileSystemInterface::EXISTS_RENAME, EXISTS_REPLACE, and EXISTS_ERROR were
 * deprecated in drupal:10.3.0 and removed in drupal:12.0.0 (issue #3575575).
 * The typed FileExists enum is the only accepted value in FileSystem::copy(),
 * ::move(), ::saveData(), and ::getDestinationFilename() from Drupal 12 onward.
 * Passing an integer constant now causes a TypeError at runtime.
 */
final class ReplaceFileExistsConstantsRector extends AbstractRector
{
    private const INTERFACE_CLASS = 'Drupal\\Core\\File\\FileSystemInterface';
    private const ENUM_CLASS      = 'Drupal\\Core\\File\\FileExists';

    /** Map of removed constant name to FileExists enum case name. */
    private const CONST_TO_ENUM_CASE = [
        'EXISTS_RENAME'  => 'Rename',
        'EXISTS_REPLACE' => 'Replace',
        'EXISTS_ERROR'   => 'Error',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed FileSystemInterface::EXISTS_* constants with FileExists enum cases',
            [
                new CodeSample(
                    '$fileSystem->copy($src, $dst, FileSystemInterface::EXISTS_RENAME);',
                    '$fileSystem->copy($src, $dst, \\Drupal\\Core\\File\\FileExists::Rename);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof ClassConstFetch) {
            return null;
        }

        // The constant name must be one of the three removed ones.
        $constName = $node->name instanceof Node\Identifier
            ? $node->name->toString()
            : null;

        if ($constName === null || !isset(self::CONST_TO_ENUM_CASE[$constName])) {
            return null;
        }

        // The class must resolve to FileSystemInterface (short name or FQCN).
        if (!$this->isName($node->class, self::INTERFACE_CLASS)) {
            return null;
        }

        return new ClassConstFetch(
            new FullyQualified(self::ENUM_CLASS),
            self::CONST_TO_ENUM_CASE[$constName]
        );
    }
}
