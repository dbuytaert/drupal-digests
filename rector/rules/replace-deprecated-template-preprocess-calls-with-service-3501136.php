<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3501136
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2.0 deprecated several template_preprocess_*() procedural
// functions and moved their logic into
// \Drupal\Core\Theme\ThemePreprocess and
// \Drupal\Core\Datetime\DatePreprocess services, registered via the
// 'initial preprocess' key in hook_theme(). Modules and themes that call
// the old functions directly (e.g. to invoke the parent preprocess) must
// switch to
// \Drupal::service(ServiceClass::class)->preprocessMethod($variables)
// before Drupal 12.0.0.
//
// Before:
//   template_preprocess_container($variables);
//
// After:
//   \Drupal::service(\Drupal\Core\Theme\ThemePreprocess::class)->preprocessContainer($variables);


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
 * Replaces deprecated template_preprocess_*() function calls with the
 * corresponding \Drupal::service(...)->method() equivalents introduced in
 * Drupal 11.2.0.
 */
final class TemplatePreprocessToServiceRector extends AbstractRector
{
    /**
     * Map of deprecated function name to [FQCN of service class, method name].
     *
     * @var array<string, array{string, string}>
     */
    private const FUNCTION_MAP = [
        'template_preprocess_time'             => ['Drupal\Core\Datetime\DatePreprocess',  'preprocessTime'],
        'template_preprocess_datetime_form'    => ['Drupal\Core\Datetime\DatePreprocess',  'preprocessDatetimeForm'],
        'template_preprocess_datetime_wrapper' => ['Drupal\Core\Datetime\DatePreprocess',  'preprocessDatetimeWrapper'],
        'template_preprocess_links'            => ['Drupal\Core\Theme\ThemePreprocess',    'preprocessLinks'],
        'template_preprocess_container'        => ['Drupal\Core\Theme\ThemePreprocess',    'preprocessContainer'],
        'template_preprocess_html'             => ['Drupal\Core\Theme\ThemePreprocess',    'preprocessHtml'],
        'template_preprocess_page'             => ['Drupal\Core\Theme\ThemePreprocess',    'preprocessPage'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated template_preprocess_*() calls with the service-based equivalents introduced in Drupal 11.2.0.',
            [
                new CodeSample(
                    'template_preprocess_container($variables);',
                    '\Drupal::service(\Drupal\Core\Theme\ThemePreprocess::class)->preprocessContainer($variables);'
                ),
            ]
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
        $funcName = $this->getName($node->name);
        if ($funcName === null || !isset(self::FUNCTION_MAP[$funcName])) {
            return null;
        }

        [$serviceClass, $methodName] = self::FUNCTION_MAP[$funcName];

        // Build \Drupal::service(\ServiceClass::class)
        $classConstFetch = new ClassConstFetch(
            new FullyQualified($serviceClass),
            'class'
        );
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConstFetch)]
        );

        // Build ->methodName($args...)
        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
