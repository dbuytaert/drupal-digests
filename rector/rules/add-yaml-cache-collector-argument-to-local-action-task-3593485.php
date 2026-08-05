<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.5 adds an optional $yaml_cache_collector constructor
 * argument to LocalActionManager, LocalTaskManager, and MenuLinkManager
 * so they can use YamlCacheCollectorDiscovery. Omitting it triggers a
 * deprecation warning and it becomes required in Drupal 12. This rule
 * appends the matching \Drupal::service(...) call as the trailing
 * argument on direct new instantiations of these three classes and on
 * parent::__construct() calls inside subclasses that extend them, only
 * when the exact prior positional argument count is present.
 *
 * Before:
 *   new \Drupal\Core\Menu\LocalActionManager($argument_resolver, $request_stack, $route_match, $route_provider, $module_handler, $cache_backend, $language_manager, $access_manager, $account);
 *
 * After:
 *   new \Drupal\Core\Menu\LocalActionManager($argument_resolver, $request_stack, $route_match, $route_provider, $module_handler, $cache_backend, $language_manager, $access_manager, $account, \Drupal::service('local_action.yaml_cache_collector'));
 *
 * Caveats:
 *   Only rewrites direct instantiations and parent::__construct() calls
 *   whose positional argument count exactly matches the pre-change
 *   signature (9 for LocalActionManager/LocalTaskManager, 3 for
 *   MenuLinkManager); calls with too few arguments, ...$args unpacking,
 *   or first-class callable syntax are left untouched for manual
 *   review. Subclasses that extend an intermediate class rather than
 *   the manager directly are not matched, since the intermediate
 *   class's constructor signature is not verified.
 *
 * @see https://www.drupal.org/node/3593485
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddYamlCacheCollectorArgumentToMenuManagersRector extends AbstractRector
{
    /**
     * Maps the manager FQCN to [service id for the new arg, position of the new (last) arg].
     * All three manager constructors name the new parameter `$yaml_cache_collector`.
     *
     * @var array<string, array{0: string, 1: int}>
     */
    private const MANAGERS = [
        'Drupal\Core\Menu\LocalActionManager' => ['local_action.yaml_cache_collector', 9],
        'Drupal\Core\Menu\LocalTaskManager' => ['local_task.yaml_cache_collector', 9],
        'Drupal\Core\Menu\MenuLinkManager' => ['menu_link.yaml_cache_collector', 3],
    ];

    private const ARGUMENT_NAME = 'yaml_cache_collector';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the new $yaml_cache_collector constructor argument to LocalActionManager, LocalTaskManager, and MenuLinkManager instantiations and to parent::__construct() calls in subclasses, since omitting it is deprecated.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
new \Drupal\Core\Menu\LocalActionManager($argument_resolver, $request_stack, $route_match, $route_provider, $module_handler, $cache_backend, $language_manager, $access_manager, $account);
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
new \Drupal\Core\Menu\LocalActionManager($argument_resolver, $request_stack, $route_match, $route_provider, $module_handler, $cache_backend, $language_manager, $access_manager, $account, \Drupal::service('local_action.yaml_cache_collector'));
CODE_SAMPLE
            )]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class, Class_::class];
    }

    /**
     * @param New_|Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof New_) {
            return $this->refactorNew($node);
        }

        if ($node instanceof Class_) {
            return $this->refactorSubclassConstructor($node);
        }

        return null;
    }

    private function refactorNew(New_ $node): ?Node
    {
        foreach (self::MANAGERS as $fqcn => [$serviceId, $position]) {
            if (!$this->isName($node->class, $fqcn)) {
                continue;
            }

            return $this->appendArgumentIfMissing($node, $position, $serviceId) ? $node : null;
        }

        return null;
    }

    private function refactorSubclassConstructor(Class_ $node): ?Node
    {
        if (!$node->extends instanceof Node\Name) {
            return null;
        }

        $target = null;
        foreach (self::MANAGERS as $fqcn => $config) {
            if ($this->isName($node->extends, $fqcn)) {
                $target = $config;
                break;
            }
        }

        if ($target === null) {
            return null;
        }
        [$serviceId, $position] = $target;

        $constructMethod = $node->getMethod('__construct');
        if (!$constructMethod instanceof ClassMethod || $constructMethod->stmts === null) {
            return null;
        }

        $hasChanged = false;
        foreach ($constructMethod->stmts as $stmt) {
            if (!$stmt instanceof Expression) {
                continue;
            }
            if (!$stmt->expr instanceof StaticCall) {
                continue;
            }
            $staticCall = $stmt->expr;
            if (!$this->isName($staticCall->class, 'parent')) {
                continue;
            }
            if (!$this->isName($staticCall->name, '__construct')) {
                continue;
            }
            if ($this->appendArgumentIfMissing($staticCall, $position, $serviceId)) {
                $hasChanged = true;
            }
        }

        return $hasChanged ? $node : null;
    }

    /**
     * @param New_|StaticCall $call
     */
    private function appendArgumentIfMissing($call, int $position, string $serviceId): bool
    {
        if ($call->isFirstClassCallable()) {
            return false;
        }

        $hasNamedArgs = false;
        foreach ($call->args as $arg) {
            if (!$arg instanceof Arg) {
                // Unpacked/variadic args make position counting unreliable.
                return false;
            }
            if ($arg->name !== null) {
                $hasNamedArgs = true;
                if ($this->isName($arg->name, self::ARGUMENT_NAME)) {
                    // Argument already present as a named argument.
                    return false;
                }
            }
        }

        $newArgValue = new StaticCall(new FullyQualified('Drupal'), 'service', [new Arg(new String_($serviceId))]);
        $newArg = new Arg($newArgValue);

        if ($hasNamedArgs) {
            $newArg->name = new Identifier(self::ARGUMENT_NAME);
            $call->args[] = $newArg;
            return true;
        }

        // Only append when exactly the required positional arguments are present;
        // anything else (too few, or the new argument already present) is left alone.
        if (count($call->args) !== $position) {
            return false;
        }

        $call->args[] = $newArg;
        return true;
    }
}
