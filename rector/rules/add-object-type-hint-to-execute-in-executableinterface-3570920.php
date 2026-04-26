<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3570920
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds the ?object type hint to the first parameter of execute()
// overrides in classes that implement ExecutableInterface or a known
// sub-interface (ActionInterface, ConditionInterface) or extend a known
// base class (ActionBase, EntityActionBase, ConditionPluginBase). The
// type was formally added to ExecutableInterface::execute() in
// drupal:12.0.0 (issue #3570908); overrides without it trigger Symfony
// DebugClassLoader deprecation warnings. Only untyped parameters are
// updated; parameters already carrying a specific type are left
// untouched.
//
// Before:
//   use Drupal\Core\Action\ActionBase;
//   
//   class BlockUserAction extends ActionBase {
//     public function execute($account = NULL) {
//       $account->block()->save();
//     }
//   }
//
// After:
//   use Drupal\Core\Action\ActionBase;
//   
//   class BlockUserAction extends ActionBase {
//     public function execute(?object $account = NULL) {
//       $account->block()->save();
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the missing `?object` type hint to execute() implementations in
 * classes that implement ExecutableInterface or any sub-interface.
 *
 * ExecutableInterface::execute() was formally typed as ?object $object = NULL
 * in drupal:12.0.0 (issue #3570908). Classes implementing this interface
 * (or sub-interfaces like ActionInterface, ConditionInterface) without the
 * ?object type hint generate Symfony DebugClassLoader deprecation warnings.
 *
 * @see https://www.drupal.org/project/drupal/issues/3570908
 * @see https://www.drupal.org/project/drupal/issues/3570920
 */
final class AddObjectTypeToExecuteMethodRector extends AbstractRector
{
    /**
     * Known interfaces whose execute() signature must have ?object.
     * Both the short name and the FQCN are accepted.
     */
    private const TARGET_INTERFACES = [
        'Drupal\\Core\\Executable\\ExecutableInterface' => 'ExecutableInterface',
        'Drupal\\Core\\Action\\ActionInterface'         => 'ActionInterface',
        'Drupal\\Core\\Condition\\ConditionInterface'   => 'ConditionInterface',
    ];

    /**
     * Known abstract base classes whose execute() subclass overrides must
     * also be typed.
     */
    private const TARGET_CLASSES = [
        'Drupal\\Core\\Action\\ActionBase'                         => 'ActionBase',
        'Drupal\\Core\\Action\\Plugin\\Action\\EntityActionBase'   => 'EntityActionBase',
        'Drupal\\Core\\Condition\\ConditionPluginBase'             => 'ConditionPluginBase',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing ?object type hint to execute() in ExecutableInterface implementations (required in drupal:12.0.0)',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Action\ActionBase;

class BlockUserAction extends ActionBase {
  public function execute($account = NULL) {
    $account->block()->save();
  }
}
CODE,
                    <<<'CODE'
use Drupal\Core\Action\ActionBase;

class BlockUserAction extends ActionBase {
  public function execute(?object $account = NULL) {
    $account->block()->save();
  }
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        if (!$this->isExecutableImplementor($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            if (!$this->isName($method, 'execute')) {
                continue;
            }
            if ($this->addObjectType($method)) {
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    /**
     * Returns true if the class directly implements or extends one of the
     * target interfaces / base classes.
     */
    private function isExecutableImplementor(Class_ $class): bool
    {
        // Check implements list against known interfaces.
        foreach ($class->implements as $iface) {
            $name = $iface->toString();
            foreach (self::TARGET_INTERFACES as $fqcn => $short) {
                if ($name === $fqcn || $name === $short || str_ends_with($name, '\\' . $short)) {
                    return true;
                }
            }
        }

        // Check extends against known base classes.
        if ($class->extends !== null) {
            $name = $class->extends->toString();
            foreach (self::TARGET_CLASSES as $fqcn => $short) {
                if ($name === $fqcn || $name === $short || str_ends_with($name, '\\' . $short)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Adds ?object type to the first parameter of the execute() method if it
     * does not already have it. Returns true when modified.
     */
    private function addObjectType(ClassMethod $method): bool
    {
        if (empty($method->params)) {
            return false;
        }

        $param = $method->params[0];

        // Already typed with ?object — skip.
        if ($param->type instanceof NullableType) {
            $inner = $param->type->type;
            if ($inner instanceof Identifier && $inner->name === 'object') {
                return false;
            }
        }

        // Has a specific (non-object) type hint — do not overwrite.
        if ($param->type !== null) {
            return false;
        }

        $param->type = new NullableType(new Identifier('object'));

        return true;
    }
}
