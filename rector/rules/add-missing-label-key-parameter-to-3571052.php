<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571052
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds string $label_key = 'label' as the second parameter to
// getSortedDefinitions() and getGroupedDefinitions() overrides in
// classes and interfaces that implement
// CategorizingPluginManagerInterface. The parameter was formally added
// to the interface in drupal:12.0.0 (issue #3571052); overrides without
// it cause a fatal signature-mismatch error on Drupal 12.
//
// Before:
//   use Drupal\Component\Plugin\CategorizingPluginManagerInterface;
//   
//   class MyPluginManager implements CategorizingPluginManagerInterface {
//     public function getSortedDefinitions(?array $definitions = NULL) {
//       return $definitions ?? $this->getDefinitions();
//     }
//     public function getGroupedDefinitions(?array $definitions = NULL) {
//       return [];
//     }
//   }
//
// After:
//   use Drupal\Component\Plugin\CategorizingPluginManagerInterface;
//   
//   class MyPluginManager implements CategorizingPluginManagerInterface {
//     public function getSortedDefinitions(?array $definitions = NULL, string $label_key = 'label') {
//       return $definitions ?? $this->getDefinitions();
//     }
//     public function getGroupedDefinitions(?array $definitions = NULL, string $label_key = 'label') {
//       return [];
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Interface_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the missing `string $label_key = 'label'` parameter to
 * getSortedDefinitions() and getGroupedDefinitions() overrides in classes/
 * interfaces that implement CategorizingPluginManagerInterface.
 *
 * In drupal:12.0.0, the parameter was officially added to the interface
 * (it had existed in the trait but was commented out of the interface for
 * backward compatibility in drupal:11.x). Any class that overrides these
 * methods without the $label_key parameter will cause a signature mismatch
 * in drupal:12.
 *
 * @see https://www.drupal.org/project/drupal/issues/3571052
 */
final class AddLabelKeyParamToCategorizingPluginManagerRector extends AbstractRector
{
    private const INTERFACE_FQCN = 'Drupal\\Component\\Plugin\\CategorizingPluginManagerInterface';
    private const METHODS = ['getSortedDefinitions', 'getGroupedDefinitions'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing `string $label_key = \'label\'` parameter to getSortedDefinitions() and getGroupedDefinitions() in CategorizingPluginManagerInterface implementations (required in drupal:12.0.0)',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Component\Plugin\CategorizingPluginManagerInterface;

class MyPluginManager implements CategorizingPluginManagerInterface {
  public function getSortedDefinitions(?array $definitions = NULL) {
    return $definitions ?? $this->getDefinitions();
  }
  public function getGroupedDefinitions(?array $definitions = NULL) {
    return [];
  }
}
CODE,
                    <<<'CODE'
use Drupal\Component\Plugin\CategorizingPluginManagerInterface;

class MyPluginManager implements CategorizingPluginManagerInterface {
  public function getSortedDefinitions(?array $definitions = NULL, string $label_key = 'label') {
    return $definitions ?? $this->getDefinitions();
  }
  public function getGroupedDefinitions(?array $definitions = NULL, string $label_key = 'label') {
    return [];
  }
}
CODE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class, Interface_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_ && !$node instanceof Interface_) {
            return null;
        }

        if (!$this->implementsCategorizingInterface($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->getMethods() as $method) {
            $methodName = $this->getName($method);
            if (!in_array($methodName, self::METHODS, true)) {
                continue;
            }

            if ($this->addLabelKeyParam($method)) {
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }

    /**
     * Returns true when the node directly references
     * CategorizingPluginManagerInterface in its implements / extends list.
     */
    private function implementsCategorizingInterface(Node $node): bool
    {
        $shortName = 'CategorizingPluginManagerInterface';

        $names = [];
        if ($node instanceof Class_) {
            $names = $node->implements;
        } elseif ($node instanceof Interface_) {
            $names = $node->extends;
        }

        foreach ($names as $name) {
            $str = $name->toString();
            if (
                $str === self::INTERFACE_FQCN
                || $str === $shortName
                || str_ends_with($str, '\\' . $shortName)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Adds `string $label_key = 'label'` as the second parameter of
     * $method if it is not already present. Returns true when modified.
     */
    private function addLabelKeyParam(ClassMethod $method): bool
    {
        foreach ($method->params as $param) {
            if ($param->var instanceof Variable
                && $param->var->name === 'label_key'
            ) {
                return false;
            }
        }

        $param = new Param(
            new Variable('label_key'),
            new String_('label'),
            new Identifier('string')
        );

        array_splice($method->params, 1, 0, [$param]);

        return true;
    }
}
