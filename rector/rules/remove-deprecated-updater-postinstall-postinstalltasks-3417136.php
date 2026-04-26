<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3417136
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes postInstall() and postInstallTasks() method overrides in
// classes that extend Drupal\Core\Updater\Updater, Module, or Theme.
// Both methods are deprecated in drupal:11.1.0 with no replacement and
// removed in drupal:12.0.0, because the entire install-via-URL flow was
// eliminated from core. Overrides are dead code — Drupal no longer calls
// them.
//
// Before:
//   use Drupal\Core\Updater\Updater;
//   
//   class MyUpdater extends Updater {
//     public function postInstallTasks() {
//       return [['#type' => 'link', '#title' => 'My task']];
//     }
//     public function postInstall() {
//       \Drupal::logger('mymodule')->info('Installed.');
//     }
//   }
//
// After:
//   use Drupal\Core\Updater\Updater;
//   
//   class MyUpdater extends Updater {
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes overrides of Updater::postInstall() and Updater::postInstallTasks()
 * in subclasses of Drupal\Core\Updater\Updater (and its known subclasses
 * Module and Theme). Both methods are deprecated in drupal:11.1.0 with no
 * replacement and removed in drupal:12.0.0.
 */
final class RemoveUpdaterPostInstallMethodsRector extends AbstractRector
{
    private const DEPRECATED_METHODS = ['postInstall', 'postInstallTasks'];

    private const UPDATER_BASE_CLASSES = [
        'Drupal\\Core\\Updater\\Updater',
        'Drupal\\Core\\Updater\\Module',
        'Drupal\\Core\\Updater\\Theme',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove overrides of deprecated Updater::postInstall() and Updater::postInstallTasks() (drupal:11.1.0, removed drupal:12.0.0)',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Updater\Updater;

class MyUpdater extends Updater {
  public function postInstallTasks() {
    return [['#type' => 'link', '#title' => 'My task']];
  }
  public function postInstall() {
    \Drupal::logger('mymodule')->info('Installed.');
  }
}
CODE,
                    <<<'CODE'
use Drupal\Core\Updater\Updater;

class MyUpdater extends Updater {
}
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        if ($node->extends === null) {
            return null;
        }

        $parentName = $node->extends->toString();
        if (!in_array($parentName, self::UPDATER_BASE_CLASSES, true)) {
            return null;
        }

        $modified = false;
        $newStmts = [];
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof ClassMethod
                && in_array($this->getName($stmt), self::DEPRECATED_METHODS, true)
            ) {
                $modified = true;
                continue;
            }
            $newStmts[] = $stmt;
        }

        if (!$modified) {
            return null;
        }

        $node->stmts = $newStmts;
        return $node;
    }
}
