<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3265945
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Plugin manager subclasses that call parent::__construct() with only a
// Drupal annotation class at argument index 4 (no attribute class)
// trigger a deprecation introduced in drupal:11.2.0 and removed in
// drupal:12.0.0. This rule inserts the corresponding PHP attribute class
// (derived by replacing \Annotation\ with \Attribute\ in the namespace
// string) at position 4 and shifts the annotation class to position 5
// for backward-compatible discovery.
//
// Before:
//   parent::__construct('Plugin/Foo', $namespaces, $module_handler, FooInterface::class, 'Drupal\mymodule\Annotation\Foo');
//
// After:
//   parent::__construct('Plugin/Foo', $namespaces, $module_handler, FooInterface::class, 'Drupal\mymodule\Attribute\Foo', 'Drupal\mymodule\Annotation\Foo');


use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rector rule: add attribute class argument to DefaultPluginManager::__construct().
 *
 * Plugin manager subclasses that call parent::__construct() with only an
 * annotation class at argument index 4 (and no attribute class) trigger a
 * deprecation in drupal:11.2.0, removed in drupal:12.0.0. This rule inserts
 * the corresponding attribute class (derived by replacing \Annotation\ with
 * \Attribute\ in the namespace) as argument 4, and shifts the annotation class
 * to argument 5 for backward-compatible discovery.
 */
final class AddPluginManagerAttributeClassRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add a PHP attribute class argument to DefaultPluginManager::__construct() calls that only pass a Drupal annotation class, fixing the drupal:11.2.0 deprecation removed in drupal:12.0.0.',
            [
                new CodeSample(
                    "parent::__construct('Plugin/Foo', \$namespaces, \$module_handler, FooInterface::class, 'Drupal\\mymodule\\Annotation\\Foo');",
                    "parent::__construct('Plugin/Foo', \$namespaces, \$module_handler, FooInterface::class, 'Drupal\\mymodule\\Attribute\\Foo', 'Drupal\\mymodule\\Annotation\\Foo');"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /** @param StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        // Only target parent::__construct() calls.
        if (!$this->isName($node->class, 'parent')) {
            return null;
        }
        if (!$this->isName($node->name, '__construct')) {
            return null;
        }

        // Must have exactly 5 positional args (no attribute class yet).
        if (count($node->args) !== 5) {
            return null;
        }

        // Arg[4] must be a plain string literal containing \Annotation\.
        $arg4 = $node->args[4]->value;
        if (!$arg4 instanceof String_) {
            return null;
        }

        $annotationClass = $arg4->value;
        // Normalise any leading backslash before testing.
        $normalised = ltrim($annotationClass, '\\');
        if (strpos($normalised, '\\Annotation\\') === false) {
            return null;
        }

        // Derive the attribute class: swap \Annotation\ for \Attribute\.
        $attributeClass = str_replace('\\Annotation\\', '\\Attribute\\', $normalised);

        // Build the new attribute-class argument and splice it in at position 4,
        // pushing the existing annotation-class argument to position 5.
        $attributeArg = new \PhpParser\Node\Arg(new String_($attributeClass));
        array_splice($node->args, 4, 0, [$attributeArg]);

        return $node;
    }
}
