<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces four deprecated procedural functions from locale.compare.inc
 * with calls on the new LocaleProjectRepository and LocaleProjectChecker
 * services introduced in Drupal 11.4.
 * locale_translation_flush_projects() and
 * locale_translation_build_projects() delegate to
 * LocaleProjectRepository, while locale_translation_check_projects() and
 * locale_translation_check_projects_local() delegate to
 * LocaleProjectChecker. When called with no arguments, the rule expands
 * the call to pass all projects explicitly.
 *
 * Before:
 *   locale_translation_flush_projects();
 *   locale_translation_build_projects();
 *   locale_translation_check_projects();
 *   locale_translation_check_projects_local(['drupal'], ['de']);
 *
 * After:
 *   \Drupal::service(\Drupal\locale\LocaleProjectRepository::class)->deleteAll();
 *   \Drupal::service(\Drupal\locale\LocaleProjectRepository::class)->buildProjects();
 *   \Drupal::service(\Drupal\locale\LocaleProjectChecker::class)->checkProjects(array_keys(\Drupal::service(\Drupal\locale\LocaleProjectRepository::class)->getAll()));
 *   \Drupal::service(\Drupal\locale\LocaleProjectChecker::class)->checkLocalProjects(['drupal'], ['de']);
 *
 * Caveats:
 *   The functions locale_translation_project_list(),
 *   _locale_translation_prepare_project_list(), and
 *   locale_translation_default_translation_server() are also deprecated
 *   in this issue but have no replacement, so they are not handled by
 *   this rule.
 *
 * @see https://www.drupal.org/node/3037031
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated locale.compare.inc functions with service calls.
 *
 * locale_translation_flush_projects() -> LocaleProjectRepository::deleteAll()
 * locale_translation_build_projects() -> LocaleProjectRepository::buildProjects()
 * locale_translation_check_projects() -> LocaleProjectChecker::checkProjects()
 * locale_translation_check_projects_local() -> LocaleProjectChecker::checkLocalProjects()
 */
final class LocaleCompareIncToServiceRector extends AbstractRector
{
    private const LOCALE_PROJECT_REPOSITORY = 'Drupal\\locale\\LocaleProjectRepository';
    private const LOCALE_PROJECT_CHECKER = 'Drupal\\locale\\LocaleProjectChecker';

    // Functions that map directly: name -> [serviceClass, method]
    // (no special empty-projects handling needed)
    private const DIRECT_MAP = [
        'locale_translation_flush_projects' => [self::LOCALE_PROJECT_REPOSITORY, 'deleteAll'],
        'locale_translation_build_projects' => [self::LOCALE_PROJECT_REPOSITORY, 'buildProjects'],
    ];

    // Functions that accept ($projects, $langcodes) and need empty-project expansion
    private const PROJECT_CHECKER_MAP = [
        'locale_translation_check_projects' => [self::LOCALE_PROJECT_CHECKER, 'checkProjects'],
        'locale_translation_check_projects_local' => [self::LOCALE_PROJECT_CHECKER, 'checkLocalProjects'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated locale.compare.inc functions with LocaleProjectRepository and LocaleProjectChecker service methods.',
            [new CodeSample(
                'locale_translation_flush_projects();
locale_translation_build_projects();
locale_translation_check_projects();
locale_translation_check_projects_local([\'drupal\'], [\'de\']);',
                '\\Drupal::service(\\Drupal\\locale\\LocaleProjectRepository::class)->deleteAll();
\\Drupal::service(\\Drupal\\locale\\LocaleProjectRepository::class)->buildProjects();
\\Drupal::service(\\Drupal\\locale\\LocaleProjectChecker::class)->checkProjects(array_keys(\\Drupal::service(\\Drupal\\locale\\LocaleProjectRepository::class)->getAll()));
\\Drupal::service(\\Drupal\\locale\\LocaleProjectChecker::class)->checkLocalProjects([\'drupal\'], [\'de\']);',
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
        if ($name === null) {
            return null;
        }

        // Simple no-args or pass-through rewrites.
        if (isset(self::DIRECT_MAP[$name])) {
            [$serviceClass, $method] = self::DIRECT_MAP[$name];
            return new MethodCall($this->buildServiceCall($serviceClass), $method, $node->args);
        }

        // Functions with ($projects, $langcodes) that need empty-projects expansion.
        if (isset(self::PROJECT_CHECKER_MAP[$name])) {
            [$serviceClass, $method] = self::PROJECT_CHECKER_MAP[$name];

            if (count($node->args) === 0) {
                // When called with no arguments, the deprecated wrapper fetched all
                // projects first. Mirror that by passing array_keys(getAll()).
                $allProjects = new FuncCall(
                    new FullyQualified('array_keys'),
                    [new Arg(new MethodCall($this->buildServiceCall(self::LOCALE_PROJECT_REPOSITORY), 'getAll', []))]
                );
                return new MethodCall($this->buildServiceCall($serviceClass), $method, [new Arg($allProjects)]);
            }

            // When called with explicit arguments, pass them through unchanged.
            return new MethodCall($this->buildServiceCall($serviceClass), $method, $node->args);
        }

        return null;
    }

    /**
     * Builds a \Drupal::service(ClassName::class) static call expression.
     */
    private function buildServiceCall(string $serviceClass): StaticCall
    {
        $classConst = new ClassConstFetch(new FullyQualified($serviceClass), 'class');
        return new StaticCall(new FullyQualified('Drupal'), 'service', [new Arg($classConst)]);
    }
}
