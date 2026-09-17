<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global functions
 * update_check_requirements(), update_system_schema_requirements(), and
 * update_do_one() (defined in core/includes/update.inc) into calls on
 * the new Drupal\Core\Update\DatabaseUpdate service, obtained via
 * \Drupal::service(DatabaseUpdate::class). For update_do_one(), the
 * $number argument is also cast to int to match the new method's strict
 * type. This helps contrib code (custom update requirement checks,
 * custom batch update runners) migrate ahead of the functions' removal.
 *
 * Before:
 *   function mymodule_update_10001() {
 *     $requirements = update_check_requirements();
 *     return $requirements;
 *   }
 *   
 *   function mymodule_batch_wrapper($module, $number, $dependency_map, &$context) {
 *     update_do_one($module, $number, $dependency_map, $context);
 *   }
 *
 * After:
 *   function mymodule_update_10001() {
 *     $requirements = \Drupal::service(\Drupal\Core\Update\DatabaseUpdate::class)->getRequirements();
 *     return $requirements;
 *   }
 *   
 *   function mymodule_batch_wrapper($module, $number, $dependency_map, &$context) {
 *     \Drupal::service(\Drupal\Core\Update\DatabaseUpdate::class)->doOne($module, (int) $number, $dependency_map, $context);
 *   }
 *
 * Caveats:
 *   Does not rewrite _update_fix_missing_schema(): that function is
 *   documented as internal-use-only with no public replacement, so it
 *   is intentionally left out. Does not rewrite the string
 *   'update_do_one' when passed as a batch operation callback name
 *   (e.g. $batch_builder->addOperation('update_do_one', [...])); that
 *   shape is a plain string literal, not a function call, and must be
 *   updated manually to DatabaseUpdate::class . ':doOne'. Calls using
 *   named arguments, argument unpacking (...$args), or a non-default
 *   arg count are left untouched to avoid mis-rewriting call shapes the
 *   rule cannot safely reason about.
 *
 * @see https://www.drupal.org/node/3391683
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\Int_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUpdateIncFunctionsWithDatabaseUpdateServiceRector extends AbstractRector
{
    /**
     * Maps deprecated global function name to the DatabaseUpdate method
     * name and the exact argument count each one requires.
     *
     * @var array<string, array{0: string, 1: int}>
     */
    private const FUNCTION_MAP = [
        'update_check_requirements' => ['getRequirements', 0],
        'update_system_schema_requirements' => ['systemSchemaRequirements', 0],
        'update_do_one' => ['doOne', 4],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace calls to the deprecated update.inc functions update_check_requirements(), update_system_schema_requirements() and update_do_one() with calls to the Drupal\Core\Update\DatabaseUpdate service.',
            [new CodeSample(
                'update_do_one($module, $number, $dependency_map, $context);',
                '\\Drupal::service(\\Drupal\\Core\\Update\\DatabaseUpdate::class)->doOne($module, (int) $number, $dependency_map, $context);',
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

        // Dynamic call target such as $fn(); the name is not a Name node.
        if (!$node->name instanceof Node\Name) {
            return null;
        }

        $functionName = $this->getName($node->name);
        if ($functionName === null || !isset(self::FUNCTION_MAP[$functionName])) {
            return null;
        }

        [$methodName, $expectedArgCount] = self::FUNCTION_MAP[$functionName];

        if (count($node->args) !== $expectedArgCount) {
            return null;
        }

        $args = $node->args;

        if ($functionName === 'update_do_one') {
            // Only rewrite when every argument is a plain positional Arg
            // (skip named args, spreads and unpacked args to stay safe).
            foreach ($args as $arg) {
                if (!$arg instanceof Arg || $arg->name !== null || $arg->unpack) {
                    return null;
                }
            }

            // update_do_one($module, $number, ...) took $number untyped;
            // doOne() requires an int, so cast it explicitly.
            $args[1] = new Arg(new Int_($args[1]->value));
        }

        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified('Drupal\\Core\\Update\\DatabaseUpdate'), 'class'))],
        );

        return new MethodCall($service, $methodName, $args);
    }
}
