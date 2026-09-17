<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In Drupal 11.4.0, RowPluginBase::render() triggers a deprecation when
 * $row is not a \Drupal\views\ResultRow instance. This rule finds
 * subclasses of RowPluginBase that override render($row) without a type
 * hint and adds the ResultRow type, preventing the deprecation and
 * preparing for the hard type error in Drupal 12.0.0.
 *
 * Before:
 *   use Drupal\views\Plugin\views\row\RowPluginBase;
 *   
 *   class MyRowPlugin extends RowPluginBase {
 *     public function render($row) {
 *       return ['#theme' => 'my_row'];
 *     }
 *   }
 *
 * After:
 *   use Drupal\views\Plugin\views\row\RowPluginBase;
 *   use Drupal\views\ResultRow;
 *   
 *   class MyRowPlugin extends RowPluginBase {
 *     public function render(ResultRow $row) {
 *       return ['#theme' => 'my_row'];
 *     }
 *   }
 *
 * Caveats:
 *   Only adds the type hint when the first parameter of render() has no
 *   existing type annotation. Parameters already carrying a non-
 *   ResultRow type (e.g. object) are skipped to avoid inadvertent
 *   changes. The rule does not add or remove use imports; Rector's
 *   auto-import pass handles that when configured.
 *
 * @see https://www.drupal.org/node/3041170
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddResultRowTypeHintToRowPluginRenderRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add ResultRow type hint to $row parameter in RowPluginBase::render() overrides to resolve drupal:11.4.0 deprecation.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\row\RowPluginBase;

class MyRowPlugin extends RowPluginBase {
  public function render($row) {
    return ['#markup' => 'hello'];
  }
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
use Drupal\views\Plugin\views\row\RowPluginBase;
use Drupal\views\ResultRow;

class MyRowPlugin extends RowPluginBase {
  public function render(ResultRow $row) {
    return ['#markup' => 'hello'];
  }
}
CODE_SAMPLE,
            )],
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
        if (!$node instanceof Class_) {
            return null;
        }
        if ($node->extends === null) {
            return null;
        }
        if (!$this->isObjectType($node->extends, new ObjectType('Drupal\\views\\Plugin\\views\\row\\RowPluginBase'))) {
            return null;
        }
        $changed = false;
        foreach ($node->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }
            if (!$this->isName($stmt->name, 'render')) {
                continue;
            }
            if (count($stmt->params) < 1) {
                continue;
            }
            $param = $stmt->params[0];
            // Already typed — skip.
            if ($param->type !== null) {
                continue;
            }
            $param->type = new FullyQualified('Drupal\\views\\ResultRow');
            $changed = true;
        }
        return $changed ? $node : null;
    }
}
