<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3579922
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds the missing array type declaration to by-reference parameters
// named $variables, $suggestions, $form, or $info in class methods
// decorated with #[Hook(...)]. Preprocess hooks, form_alter hooks,
// theme_suggestions_alter hooks, and element_info_alter always receive
// those arguments as arrays; the explicit type hint improves static
// analysis and aligns with Drupal's move toward stricter typing.
//
// Before:
//   use Drupal\Core\Hook\Attribute\Hook;
//   
//   class OliveroHooks {
//       #[Hook('preprocess_node')]
//       public function preprocessNode(&$variables): void {}
//   }
//
// After:
//   use Drupal\Core\Hook\Attribute\Hook;
//   
//   class OliveroHooks {
//       #[Hook('preprocess_node')]
//       public function preprocessNode(array &$variables): void {}
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds array type hint to by-reference parameters in #[Hook]-decorated methods.
 *
 * Preprocess hooks, form_alter hooks, theme_suggestions_alter hooks, and
 * element_info_alter hooks always receive their primary argument (e.g.
 * $variables, $form, $suggestions, $info) as an array. This rule adds the
 * missing explicit `array` type declaration to those untyped by-reference
 * parameters.
 */
final class AddArrayTypeToHookParametersRector extends AbstractRector
{
    /**
     * Parameter names that are known to be arrays in hook implementations.
     */
    private const ARRAY_PARAM_NAMES = ['variables', 'suggestions', 'form', 'info'];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add array type hint to known array by-reference parameters in #[Hook]-decorated class methods',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\Core\Hook\Attribute\Hook;

class OliveroHooks {
    #[Hook('preprocess_node')]
    public function preprocessNode(&$variables): void {}
}
CODE,
                    <<<'CODE'
use Drupal\Core\Hook\Attribute\Hook;

class OliveroHooks {
    #[Hook('preprocess_node')]
    public function preprocessNode(array &$variables): void {}
}
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /** @param ClassMethod $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->hasHookAttribute($node)) {
            return null;
        }

        $changed = false;
        foreach ($node->params as $param) {
            // Skip params that already have a type hint.
            if ($param->type !== null) {
                continue;
            }
            // Only target by-reference params with known array names.
            if (!$param->byRef) {
                continue;
            }
            $paramName = $this->getName($param->var);
            if (!in_array($paramName, self::ARRAY_PARAM_NAMES, true)) {
                continue;
            }
            $param->type = new Identifier('array');
            $changed = true;
        }

        return $changed ? $node : null;
    }

    private function hasHookAttribute(ClassMethod $node): bool
    {
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $this->getName($attr->name);
                if ($name === 'Hook' || $name === 'Drupal\\Core\\Hook\\Attribute\\Hook') {
                    return true;
                }
            }
        }
        return false;
    }
}
