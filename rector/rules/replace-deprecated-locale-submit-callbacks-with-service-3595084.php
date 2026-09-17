<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites direct calls to three deprecated locale.module procedural
 * functions — locale_system_set_config_langcodes(),
 * locale_form_language_admin_add_form_alter_submit(), and
 * locale_form_language_admin_edit_form_alter_submit() — to their
 * respective service-based replacements. The first delegates to
 * locale.config_manager, while the latter two delegate to
 * Drupal\locale\Hook\LocaleFormHooks. All three were deprecated in
 * Drupal 11.5.0.
 *
 * Before:
 *   locale_system_set_config_langcodes();
 *
 * After:
 *   \Drupal::service('locale.config_manager')->updateDefaultConfigLangcodes();
 *
 * Caveats:
 *   Does not rewrite string-literal callback references such as
 *   $form['#submit'][] =
 *   'locale_form_language_admin_add_form_alter_submit'; those must be
 *   updated manually to a service-based callable.
 *
 * @see https://www.drupal.org/node/3595084
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LocaleDeprecatedCallbacksRector extends AbstractRector
{
    // Maps deprecated function name => [service_id, replacement_method]
    private const REPLACEMENTS = [
        'locale_system_set_config_langcodes' => ['locale.config_manager', 'updateDefaultConfigLangcodes'],
        'locale_form_language_admin_add_form_alter_submit' => ['Drupal\\locale\\Hook\\LocaleFormHooks', 'formLanguageAdminAddFormAlterSubmit'],
        'locale_form_language_admin_edit_form_alter_submit' => ['Drupal\\locale\\Hook\\LocaleFormHooks', 'formLanguageAdminEditFormAlterSubmit'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale procedural submit callbacks with service method calls.',
            [new CodeSample(
                'locale_system_set_config_langcodes();',
                "\\Drupal::service('locale.config_manager')->updateDefaultConfigLangcodes();",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall) {
            return null;
        }
        $name = $this->getName($node->name);
        if ($name === null || !isset(self::REPLACEMENTS[$name])) {
            return null;
        }
        [$serviceId, $method] = self::REPLACEMENTS[$name];
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_($serviceId))],
        );
        return new MethodCall($serviceCall, $method, $node->args);
    }
}
