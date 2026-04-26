<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3474123
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal issue #3474123 restructured the navigation.settings config
// schema so that logo-related keys move from a flat logo_xxx naming
// convention to a nested logo mapping. Calls to ->get('logo_provider'),
// ->get('logo_height'), ->set('logo_managed', ...), etc., and
// #config_target strings using the old keys must be updated. Failing to
// do so causes config reads and writes to silently return null or fail
// validation.
//
// Before:
//   $config->get('logo_provider');
//   $config->get('logo_managed');
//   $config->get('logo_max_filesize');
//   $config->get('logo_height');
//   $config->get('logo_width');
//   $config->set('logo_managed', $fid);
//   $form['x']['#config_target'] = 'navigation.settings:logo_provider';
//
// After:
//   $config->get('logo.provider');
//   $config->get('logo.managed');
//   $config->get('logo.max.filesize');
//   $config->get('logo.max.height');
//   $config->get('logo.max.width');
//   $config->set('logo.managed', $fid);
//   $form['x']['#config_target'] = 'navigation.settings:logo.provider';


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames navigation.settings config keys from flat logo_xxx to nested logo.xxx.
 *
 * Drupal issue #3474123 restructured the Navigation module settings schema so
 * that logo-related keys are nested under a 'logo' mapping instead of using
 * flat underscore-separated names.
 */
final class NavigationSettingsLogoKeysRector extends AbstractRector
{
    /**
     * Maps old flat config key names to their new nested equivalents.
     */
    private const KEY_MAP = [
        'logo_provider'      => 'logo.provider',
        'logo_managed'       => 'logo.managed',
        'logo_max_filesize'  => 'logo.max.filesize',
        'logo_height'        => 'logo.max.height',
        'logo_width'         => 'logo.max.width',
    ];

    /**
     * Maps old #config_target strings to their new equivalents.
     */
    private const CONFIG_TARGET_MAP = [
        'navigation.settings:logo_provider'     => 'navigation.settings:logo.provider',
        'navigation.settings:logo_managed'      => 'navigation.settings:logo.managed',
        'navigation.settings:logo_max_filesize' => 'navigation.settings:logo.max.filesize',
        'navigation.settings:logo_height'       => 'navigation.settings:logo.max.height',
        'navigation.settings:logo_width'        => 'navigation.settings:logo.max.width',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename navigation.settings config keys from flat logo_xxx format to nested logo.xxx format (Drupal #3474123)',
            [
                new CodeSample(
                    "\$config->get('logo_provider');",
                    "\$config->get('logo.provider');"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class, String_::class];
    }

    /**
     * @param MethodCall|String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof MethodCall) {
            return $this->refactorMethodCall($node);
        }

        if ($node instanceof String_) {
            return $this->refactorConfigTargetString($node);
        }

        return null;
    }

    private function refactorMethodCall(MethodCall $node): ?MethodCall
    {
        $methodName = $this->getName($node->name);
        if (!in_array($methodName, ['get', 'set'], true)) {
            return null;
        }

        if (empty($node->args) || !$node->args[0] instanceof Node\Arg) {
            return null;
        }

        $firstArgValue = $node->args[0]->value;
        if (!$firstArgValue instanceof String_) {
            return null;
        }

        $oldKey = $firstArgValue->value;
        if (!isset(self::KEY_MAP[$oldKey])) {
            return null;
        }

        $firstArgValue->value = self::KEY_MAP[$oldKey];
        return $node;
    }

    private function refactorConfigTargetString(String_ $node): ?String_
    {
        if (!isset(self::CONFIG_TARGET_MAP[$node->value])) {
            return null;
        }

        $node->value = self::CONFIG_TARGET_MAP[$node->value];
        return $node;
    }
}
