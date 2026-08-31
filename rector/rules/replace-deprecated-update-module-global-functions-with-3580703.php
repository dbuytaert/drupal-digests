<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 deprecates five update.module procedural functions in
 * favor of methods on the update.manager and update.processor services,
 * removed in Drupal 13. This rule rewrites update_get_available(),
 * update_refresh(), update_storage_clear(), update_create_fetch_task(),
 * and update_fetch_data() calls to the equivalent
 * \Drupal::service(...)->method(...) call, preserving all arguments. It
 * skips dynamic calls ($fn()) where the function name is not statically
 * known, and leaves unrelated functions of the same short name alone.
 *
 * Before:
 *   $available = update_get_available(TRUE);
 *   update_refresh();
 *   update_storage_clear();
 *   update_create_fetch_task($project);
 *   update_fetch_data();
 *
 * After:
 *   $available = \Drupal::service('update.manager')->getAvailable(TRUE);
 *   \Drupal::service('update.manager')->refreshUpdateData();
 *   \Drupal::service('update.manager')->reset();
 *   \Drupal::service('update.processor')->createFetchTask($project);
 *   \Drupal::service('update.processor')->fetchData();
 *
 * Caveats:
 *   Does not cover update_fetch_data_finished() or
 *   _update_project_status_sort(), which core deprecates with no
 *   replacement, nor _update_no_data()/_update_message_text(), whose
 *   replacements are protected methods on
 *   \Drupal\update\UpdateMessageTrait that cannot be called without a
 *   class using that trait; those call sites need manual review.
 *
 * @see https://www.drupal.org/node/3580703
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUpdateModuleFunctionsRector extends AbstractRector
{
    /**
     * Maps a deprecated update.module global function to the [service id,
     * method name] that replaces it. All of these functions were deprecated
     * in drupal:11.5.0 and are removed from drupal:13.0.0.
     *
     * @var array<string, array{0: string, 1: string}>
     */
    private const FUNCTION_MAP = [
        'update_get_available' => ['update.manager', 'getAvailable'],
        'update_refresh' => ['update.manager', 'refreshUpdateData'],
        'update_storage_clear' => ['update.manager', 'reset'],
        'update_create_fetch_task' => ['update.processor', 'createFetchTask'],
        'update_fetch_data' => ['update.processor', 'fetchData'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated update.module global functions with calls to the update.manager and update.processor services.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$available = update_get_available(TRUE);
update_storage_clear();
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$available = \Drupal::service('update.manager')->getAvailable(TRUE);
\Drupal::service('update.manager')->reset();
CODE_SAMPLE,
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
        if (!$node->name instanceof Name) {
            // Dynamic call, e.g. $fn(); the function name isn't statically known.
            return null;
        }
        $functionName = $this->getName($node->name);
        if ($functionName === null || !isset(self::FUNCTION_MAP[$functionName])) {
            return null;
        }
        [$serviceId, $method] = self::FUNCTION_MAP[$functionName];

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_($serviceId))],
        );

        return new MethodCall($serviceCall, $method, $node->args);
    }
}
