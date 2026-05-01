<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Eight procedural functions from core/modules/views_ui/admin.inc are
 * deprecated in drupal:11.4.0 and removed in drupal:13.0.0. Four
 * (addAjaxTrigger, ajaxUpdateForm, standardDisplayDropdown,
 * buildFormUrl) move to protected/public instance methods on
 * ViewsFormAjaxHelperTrait / ViewsFormHelperTrait; four others
 * (addLimitedValidation, addAjaxWrapper, noJsSubmit,
 * formButtonWasClicked) become public static methods. The rule rewrites
 * all eight inside class methods and auto-injects the required trait use
 * statements when not already present.
 *
 * Before:
 *   views_ui_add_ajax_trigger($form['style'], 'style_plugin', ['displays', 'page', 'options']);
 *   $url = views_ui_build_form_url($form_state);
 *   return views_ui_add_limited_validation($element, $form_state);
 *
 * After:
 *   $this->addAjaxTrigger($form['style'], 'style_plugin', ['displays', 'page', 'options']);
 *   $url = $this->buildFormUrl($form_state);
 *   return \Drupal\views\ViewsFormAjaxHelperTrait::addLimitedValidation($element, $form_state);
 *
 * Caveats:
 *   Only rewrites calls made inside class methods. Rare procedural-
 *   scope calls to the four public-static functions
 *   (views_ui_add_limited_validation, views_ui_add_ajax_wrapper,
 *   views_ui_nojs_submit, views_ui_form_button_was_clicked) outside any
 *   class are not rewritten. Trait injection relies on PHPStan scope
 *   for parent-chain detection; if scope is unavailable the rule falls
 *   back to inspecting direct use statements in the same class, which
 *   may add a redundant use statement when the trait is inherited.
 *
 * @see https://www.drupal.org/node/3035340
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Reflection\ClassReflection;
use Rector\PHPStan\ScopeFetcher;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ViewsUiAdminDeprecatedFunctionsRector extends AbstractRector
{
    /**
     * Deprecated function name => [new method name, required trait FQCN].
     * These are now protected/public instance methods on the traits.
     */
    private const INSTANCE_REPLACEMENTS = [
        'views_ui_add_ajax_trigger'          => ['addAjaxTrigger',         'Drupal\\views\\ViewsFormAjaxHelperTrait'],
        'views_ui_ajax_update_form'           => ['ajaxUpdateForm',          'Drupal\\views\\ViewsFormAjaxHelperTrait'],
        'views_ui_standard_display_dropdown'  => ['standardDisplayDropdown', 'Drupal\\views\\ViewsFormHelperTrait'],
        'views_ui_build_form_url'             => ['buildFormUrl',            'Drupal\\views\\ViewsFormHelperTrait'],
    ];

    /**
     * Deprecated function name => [trait FQCN, new static method name].
     * These are public static methods on the traits and can be called directly.
     */
    private const STATIC_REPLACEMENTS = [
        'views_ui_add_limited_validation'  => ['Drupal\\views\\ViewsFormAjaxHelperTrait', 'addLimitedValidation'],
        'views_ui_add_ajax_wrapper'        => ['Drupal\\views\\ViewsFormAjaxHelperTrait', 'addAjaxWrapper'],
        'views_ui_nojs_submit'             => ['Drupal\\views\\ViewsFormAjaxHelperTrait', 'noJsSubmit'],
        'views_ui_form_button_was_clicked' => ['Drupal\\views\\ViewsFormHelperTrait',     'formButtonWasClicked'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated views_ui admin.inc procedural functions with ViewsFormHelperTrait / ViewsFormAjaxHelperTrait methods (drupal:11.4.0).',
            [new CodeSample(
                'views_ui_add_ajax_trigger($form, \'type\', [\'displays\']);',
                '$this->addAjaxTrigger($form, \'type\', [\'displays\']);',
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
        $hasChanged = false;
        $traitsNeeded = [];

        foreach ($node->getMethods() as $classMethod) {
            $this->traverseNodesWithCallable($classMethod, function (Node $innerNode) use (&$hasChanged, &$traitsNeeded): ?Node {
                if (!$innerNode instanceof FuncCall) {
                    return null;
                }

                $funcName = $this->getName($innerNode->name);
                if ($funcName === null) {
                    return null;
                }

                if (isset(self::STATIC_REPLACEMENTS[$funcName])) {
                    [$traitFqn, $method] = self::STATIC_REPLACEMENTS[$funcName];
                    $hasChanged = true;
                    return $this->nodeFactory->createStaticCall($traitFqn, $method, $innerNode->args);
                }

                if (isset(self::INSTANCE_REPLACEMENTS[$funcName])) {
                    [$method, $traitFqn] = self::INSTANCE_REPLACEMENTS[$funcName];
                    $traitsNeeded[$traitFqn] = true;
                    $hasChanged = true;
                    return $this->nodeFactory->createMethodCall('this', $method, $innerNode->args);
                }

                return null;
            });
        }

        if (!$hasChanged) {
            return null;
        }

        foreach (array_keys($traitsNeeded) as $traitFqn) {
            if (!$this->classOrParentUsesTrait($node, $traitFqn)) {
                array_unshift($node->stmts, new TraitUse([new FullyQualified($traitFqn)]));
            }
        }

        return $node;
    }

    private function classOrParentUsesTrait(Class_ $class, string $traitFqn): bool
    {
        // Check direct trait uses declared in this class.
        foreach ($class->stmts as $stmt) {
            if (!$stmt instanceof TraitUse) {
                continue;
            }
            foreach ($stmt->traits as $trait) {
                if ($this->getName($trait) === $traitFqn) {
                    return true;
                }
            }
        }

        // Try to check the full class hierarchy via PHPStan reflection.
        try {
            $scope = ScopeFetcher::fetch($class);
            $classReflection = $scope->getClassReflection();
            if ($classReflection instanceof ClassReflection) {
                return $classReflection->hasTraitUse($traitFqn);
            }
        } catch (\Throwable $e) {
            // Scope unavailable – fall through to false.
        }

        return false;
    }
}
