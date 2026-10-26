<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 12.1 renames hook_system_info_alter() to
 * hook_extension_info_alter(). The old hook is still invoked through
 * alterDeprecated() until 13.0. This rule rewrites
 * #[Hook('system_info_alter')] attributes and procedural
 * mymodule_system_info_alter() functions in .module files to the new
 * hook name.
 *
 * Before:
 *   #[Hook('system_info_alter')]
 *   public function systemInfoAlter(array &$info, Extension $file, $type): void {}
 *   
 *   // In mymodule.module:
 *   function mymodule_system_info_alter(array &$info, $file, $type) {}
 *
 * After:
 *   #[Hook('extension_info_alter')]
 *   public function systemInfoAlter(array &$info, Extension $file, $type): void {}
 *   
 *   // In mymodule.module:
 *   function mymodule_extension_info_alter(array &$info, $file, $type) {}
 *
 * Caveats:
 *   The new hook only exists from Drupal 12.1, so do not apply this to
 *   modules that must still support 11.x (the CR describes how to
 *   support both). Hook method names such as systemInfoAlter are left
 *   unchanged to avoid breaking callers. Procedural rewrites only apply
 *   to functions named exactly <module>_system_info_alter in
 *   <module>.module, and requires the .module extension to be processed
 *   (configured by the rule).
 *
 * @see https://www.drupal.org/node/3623862
 * @deprecated drupal:12.1.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Function_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RenameSystemInfoAlterHookRector extends AbstractRector
{
    private const OLD = 'system_info_alter';
    private const NEW = 'extension_info_alter';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename hook_system_info_alter() implementations to hook_extension_info_alter().',
            [new CodeSample(
                "#[Hook('system_info_alter')]\npublic function systemInfoAlter(array &\$info, Extension \$file, \$type): void {}",
                "#[Hook('extension_info_alter')]\npublic function systemInfoAlter(array &\$info, Extension \$file, \$type): void {}",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Attribute::class, Function_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Attribute) {
            if (!$this->isName($node->name, 'Drupal\\Core\\Hook\\Attribute\\Hook')) {
                return null;
            }
            $arg = $node->args[0] ?? null;
            if ($arg === null) {
                return null;
            }
            // Only the first positional argument or the named `hook` argument.
            if ($arg->name !== null && !$this->isName($arg->name, 'hook')) {
                return null;
            }
            if (!$arg->value instanceof String_ || $arg->value->value !== self::OLD) {
                return null;
            }
            $arg->value = new String_(self::NEW);
            return $node;
        }

        if ($node instanceof Function_) {
            $path = $this->getFile()->getFilePath();
            if (!str_ends_with($path, '.module')) {
                return null;
            }
            $module = basename($path, '.module');
            $name = $node->name->toString();
            if ($name !== $module . '_' . self::OLD) {
                return null;
            }
            $node->name = new Identifier($module . '_' . self::NEW);
            return $node;
        }

        return null;
    }
}
