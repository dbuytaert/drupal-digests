<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal core deprecated three update.module global functions in favor
 * of service methods: update_process_project_info() and
 * update_calculate_project_update_status() moved to the internal
 * Drupal\update\UpdateCalculator service, and
 * update_calculate_project_data() moved to
 * Drupal\update\UpdateManagerInterface::calculateProjectData(). This
 * rule rewrites call sites to the equivalent service calls, including
 * converting the by-reference update_calculate_project_update_status()
 * call into an assignment that wraps arguments in the new
 * UpdateProject/UpdateServerProjectInfo value objects and unwraps the
 * result with toArray(), mirroring the deprecated function's own
 * forwarding implementation.
 *
 * Before:
 *   update_process_project_info($projects);
 *   $data = update_calculate_project_data($available);
 *   update_calculate_project_update_status($project_data, $available);
 *
 * After:
 *   \Drupal::service(\Drupal\update\UpdateCalculator::class)->processProjectInfo($projects);
 *   $data = \Drupal::service(\Drupal\update\UpdateManagerInterface::class)->calculateProjectData($available);
 *   $project_data = \Drupal::service(\Drupal\update\UpdateCalculator::class)->updateProjectStatus(\Drupal\update\UpdateProject::createFromArray($project_data), \Drupal\update\UpdateServerProjectInfo::createFromArray($available))->toArray();
 *
 * Caveats:
 *   Calls using named arguments or argument unpacking (...$args) are
 *   skipped since the replacement shape depends on positional argument
 *   order; these are rare for internal update.module functions. Calls
 *   with an unexpected argument count are left untouched rather than
 *   guessed at.
 *
 * @see https://www.drupal.org/node/3580705
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class UpdateCompareFunctionsToServiceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated update.compare.inc global functions with calls to the update.update_calculator / update.manager services.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
update_process_project_info($projects);
$data = update_calculate_project_data($available);
update_calculate_project_update_status($project_data, $available);
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
\Drupal::service(\Drupal\update\UpdateCalculator::class)->processProjectInfo($projects);
$data = \Drupal::service(\Drupal\update\UpdateManagerInterface::class)->calculateProjectData($available);
$project_data = \Drupal::service(\Drupal\update\UpdateCalculator::class)->updateProjectStatus(\Drupal\update\UpdateProject::createFromArray($project_data), \Drupal\update\UpdateServerProjectInfo::createFromArray($available))->toArray();
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
        if (!$this->isName($node->name, 'update_process_project_info')
            && !$this->isName($node->name, 'update_calculate_project_data')
            && !$this->isName($node->name, 'update_calculate_project_update_status')
        ) {
            return null;
        }

        // Skip named args / spread args: the replacement shape depends on
        // positional argument order.
        foreach ($node->args as $arg) {
            if (!$arg instanceof Arg || $arg->name !== null || $arg->unpack) {
                return null;
            }
        }

        if ($this->isName($node->name, 'update_process_project_info')) {
            if (count($node->args) !== 1) {
                return null;
            }
            return $this->createServiceMethodCall('Drupal\\update\\UpdateCalculator', 'processProjectInfo', $node->args);
        }

        if ($this->isName($node->name, 'update_calculate_project_data')) {
            if (count($node->args) !== 1) {
                return null;
            }
            return $this->createServiceMethodCall('Drupal\\update\\UpdateManagerInterface', 'calculateProjectData', $node->args);
        }

        // update_calculate_project_update_status(&$project_data, $available): void
        // mutates $project_data by reference. The replacement method takes
        // value objects and returns the new UpdateProject, so we rebuild the
        // call as an assignment back onto the original first-argument
        // expression, mirroring the deprecated function's own body.
        if (count($node->args) !== 2) {
            return null;
        }
        $projectDataArg = $node->args[0];
        $availableArg = $node->args[1];

        $updateProject = new StaticCall(
            new FullyQualified('Drupal\\update\\UpdateProject'),
            'createFromArray',
            [$projectDataArg],
        );
        $updateServerProjectInfo = new StaticCall(
            new FullyQualified('Drupal\\update\\UpdateServerProjectInfo'),
            'createFromArray',
            [$availableArg],
        );
        $serviceCall = $this->createServiceMethodCall(
            'Drupal\\update\\UpdateCalculator',
            'updateProjectStatus',
            [new Arg($updateProject), new Arg($updateServerProjectInfo)],
        );
        $toArrayCall = new MethodCall($serviceCall, 'toArray');

        return new Assign($projectDataArg->value, $toArrayCall);
    }

    /**
     * @param Arg[] $args
     */
    private function createServiceMethodCall(string $serviceClass, string $method, array $args): MethodCall
    {
        $serviceLookup = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified($serviceClass), 'class'))],
        );

        return new MethodCall($serviceLookup, $method, $args);
    }
}
