<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces six deprecated filter.module procedural functions —
 * filter_formats(), filter_get_roles_by_format(),
 * filter_get_formats_by_role(), filter_default_format(), and
 * filter_fallback_format() — with equivalent calls on the
 * FilterFormatRepositoryInterface service or on
 * FilterFormatInterface::getRoles(). All were deprecated in
 * drupal:11.4.0 and removed in drupal:13.0.0.
 *
 * Before:
 *   filter_fallback_format();
 *   filter_formats();
 *   filter_formats($account);
 *   filter_get_roles_by_format($format);
 *   filter_get_formats_by_role($rid);
 *   filter_default_format();
 *   filter_default_format($account);
 *
 * After:
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getFallbackFormatId();
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getAllFormats();
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getFormatsForAccount($account);
 *   $format->getRoles();
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getFormatsByRole($rid);
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getDefaultFormat()->id();
 *   \Drupal::service(\Drupal\filter\FilterFormatRepositoryInterface::class)->getDefaultFormat($account)->id();
 *
 * @see https://www.drupal.org/node/2536594
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FilterFormatFunctionsToServiceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated filter module procedural functions with FilterFormatRepositoryInterface service methods.',
            [
                new CodeSample(
                    'filter_fallback_format();',
                    '\\Drupal::service(\\Drupal\\filter\\FilterFormatRepositoryInterface::class)->getFallbackFormatId();'
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
        $name = $this->getName($node);
        if ($name === null) {
            return null;
        }

        switch ($name) {
            case 'filter_fallback_format':
                // filter_fallback_format()
                //   → \Drupal::service(FilterFormatRepositoryInterface::class)->getFallbackFormatId()
                return $this->buildServiceMethodCall('getFallbackFormatId', []);

            case 'filter_formats':
                if (count($node->args) === 0) {
                    // filter_formats()
                    //   → \Drupal::service(FilterFormatRepositoryInterface::class)->getAllFormats()
                    return $this->buildServiceMethodCall('getAllFormats', []);
                }
                // filter_formats($account)
                //   → \Drupal::service(FilterFormatRepositoryInterface::class)->getFormatsForAccount($account)
                return $this->buildServiceMethodCall('getFormatsForAccount', [$node->args[0]->value]);

            case 'filter_get_roles_by_format':
                // filter_get_roles_by_format($format)  →  $format->getRoles()
                if (count($node->args) < 1) {
                    return null;
                }
                return $this->nodeFactory->createMethodCall($node->args[0]->value, 'getRoles');

            case 'filter_get_formats_by_role':
                // filter_get_formats_by_role($rid)
                //   → \Drupal::service(FilterFormatRepositoryInterface::class)->getFormatsByRole($rid)
                if (count($node->args) < 1) {
                    return null;
                }
                return $this->buildServiceMethodCall('getFormatsByRole', [$node->args[0]->value]);

            case 'filter_default_format':
                // filter_default_format()       → service->getDefaultFormat()->id()
                // filter_default_format($acct)  → service->getDefaultFormat($acct)->id()
                $args = count($node->args) > 0 ? [$node->args[0]->value] : [];
                $getDefault = $this->buildServiceMethodCall('getDefaultFormat', $args);
                return $this->nodeFactory->createMethodCall($getDefault, 'id');
        }

        return null;
    }

    /**
     * Builds \Drupal::service(FilterFormatRepositoryInterface::class)->$method(...$argExprs).
     *
     * @param \PhpParser\Node\Expr[] $argExprs
     */
    private function buildServiceMethodCall(string $method, array $argExprs): MethodCall
    {
        $classConst = $this->nodeFactory->createClassConstFetch(
            'Drupal\\filter\\FilterFormatRepositoryInterface',
            'class'
        );
        $serviceCall = $this->nodeFactory->createStaticCall('Drupal', 'service', [$classConst]);
        return $this->nodeFactory->createMethodCall($serviceCall, $method, $argExprs);
    }
}
