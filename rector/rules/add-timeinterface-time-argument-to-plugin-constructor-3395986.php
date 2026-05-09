<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Six Drupal plugin classes gained a required ?TimeInterface $time
 * constructor parameter in drupal:10.3.0 (required in drupal:11.0.0):
 * TimestampFormatter, views\argument\Date, datetime\argument\Date,
 * history\HistoryUserTimestamp, views\cache\Time, and views\field\Date.
 * This rule finds subclasses that override __construct without the new
 * argument, adds ?TimeInterface $time = null at the correct position,
 * and also updates the corresponding parent::__construct() call.
 *
 * Before:
 *   use Drupal\views\Plugin\views\cache\Time;
 *   use Drupal\Core\Datetime\DateFormatterInterface;
 *   class MyCache extends Time {
 *     public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter) {
 *       parent::__construct($configuration, $plugin_id, $plugin_definition, $date_formatter);
 *     }
 *   }
 *
 * After:
 *   use Drupal\views\Plugin\views\cache\Time;
 *   use Drupal\Core\Datetime\DateFormatterInterface;
 *   class MyCache extends Time {
 *     public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter, ?\Drupal\Component\Datetime\TimeInterface $time = null) {
 *       parent::__construct($configuration, $plugin_id, $plugin_definition, $date_formatter, $time);
 *     }
 *   }
 *
 * Caveats:
 *   Requires Drupal core source (or stubs) on the analysis path so that
 *   isObjectType can resolve the class hierarchy. Subclasses that do
 *   not call parent::__construct() at all will have their signature
 *   updated but no parent call will be inserted. The rule appends $time
 *   at the end of the parent::__construct() argument list, which is
 *   correct only when no arguments were previously skipped between the
 *   last positional arg and $position.
 *
 * @see https://www.drupal.org/node/3395986
 * @deprecated drupal:10.3.0
 * @removed drupal:11.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the missing $time (TimeInterface) argument to __construct() overrides
 * in classes that extend plugin base classes updated in drupal:10.3.0.
 *
 * Affected classes (deprecated in 10.3.0, required in 11.0.0):
 *   - Drupal\Core\Field\Plugin\Field\FieldFormatter\TimestampFormatter (pos 9)
 *   - Drupal\views\Plugin\views\argument\Date (pos 5)
 *   - Drupal\datetime\Plugin\views\argument\Date (pos 5)
 *   - Drupal\history\Plugin\views\filter\HistoryUserTimestamp (pos 3)
 *   - Drupal\views\Plugin\views\cache\Time (pos 4)
 *   - Drupal\views\Plugin\views\field\Date (pos 5)
 */
final class AddTimeInterfaceToPluginConstructorsRector extends AbstractRector
{
    /**
     * Map: FQCN of the parent class → 0-based position of the new $time param.
     */
    private const TARGET_CLASSES = [
        'Drupal\Core\Field\Plugin\Field\FieldFormatter\TimestampFormatter' => 9,
        'Drupal\views\Plugin\views\argument\Date'                          => 5,
        'Drupal\datetime\Plugin\views\argument\Date'                       => 5,
        'Drupal\history\Plugin\views\filter\HistoryUserTimestamp'          => 3,
        'Drupal\views\Plugin\views\cache\Time'                             => 4,
        'Drupal\views\Plugin\views\field\Date'                             => 5,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing $time (TimeInterface) argument to __construct() overrides in subclasses of Drupal plugin classes updated in drupal:10.3.0.',
            [new CodeSample(
                <<<'CODE'
use Drupal\views\Plugin\views\cache\Time;
use Drupal\Core\Datetime\DateFormatterInterface;
class MyCache extends Time {
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $date_formatter);
  }
}
CODE,
                <<<'CODE'
use Drupal\views\Plugin\views\cache\Time;
use Drupal\Core\Datetime\DateFormatterInterface;
class MyCache extends Time {
  public function __construct(array $configuration, $plugin_id, $plugin_definition, DateFormatterInterface $date_formatter, ?\Drupal\Component\Datetime\TimeInterface $time = null) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $date_formatter, $time);
  }
}
CODE,
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
        $position = $this->getTargetPosition($node);
        if ($position === null) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $classMethod) {
            if (!$this->isName($classMethod, '__construct')) {
                continue;
            }
            if ($this->constructorAlreadyHasTimeParam($classMethod, $position)) {
                continue;
            }

            // Add ?TimeInterface $time = null to the method signature.
            $param = new Param(
                new Variable('time'),
                new \PhpParser\Node\Expr\ConstFetch(new Name('null')),
            );
            $param->type = new NullableType(
                new Name\FullyQualified('Drupal\Component\Datetime\TimeInterface')
            );
            $classMethod->params[$position] = $param;

            // Update any parent::__construct() call in the body to pass $time.
            $this->updateParentConstructCall($classMethod, $position);

            $changed = true;
        }

        return $changed ? $node : null;
    }

    /**
     * Returns the 0-based $time parameter position for the given class when it
     * extends one of the targeted plugin base classes, or null otherwise.
     */
    private function getTargetPosition(Class_ $class): ?int
    {
        foreach (self::TARGET_CLASSES as $parentClass => $position) {
            if ($this->isObjectType($class, new ObjectType($parentClass))) {
                return $position;
            }
        }
        return null;
    }

    /**
     * Returns true when the constructor already declares a $time parameter at
     * the expected position.
     */
    private function constructorAlreadyHasTimeParam(ClassMethod $classMethod, int $position): bool
    {
        // Scan ALL params, not just at the expected position. A subclass may
        // already declare a $time param at a different index; adding another
        // at $position would collide and produce invalid PHP.
        foreach ($classMethod->params as $param) {
            if ($this->isName($param, 'time')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Appends $time to the first parent::__construct() call found in the
     * method body, when the argument is not yet present at $position.
     */
    private function updateParentConstructCall(ClassMethod $classMethod, int $position): void
    {
        $this->traverseNodesWithCallable(
            (array) $classMethod->stmts,
            function (Node $node) use ($position): ?Node {
                if (!$node instanceof StaticCall) {
                    return null;
                }
                if (!$node->class instanceof Name || !$this->isName($node->class, 'parent')) {
                    return null;
                }
                if (!$this->isName($node->name, '__construct')) {
                    return null;
                }
                if (isset($node->args[$position])) {
                    return null;
                }
                $node->args[] = new Arg(new Variable('time'));
                return $node;
            }
        );
    }
}
