<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In drupal:11.4.0, LibraryDiscoveryParser and YamlRouteDiscovery gained
 * a required $yamlCacheCollector constructor argument. Omitting it
 * triggers a deprecation warning and will be a fatal error in
 * drupal:12.0.0. This rule adds
 * \Drupal::service('library.parsing_cache') or
 * \Drupal::service('routing.yaml_cache_collector') as the missing
 * argument when the pre-deprecation positional argument count is
 * detected.
 *
 * Before:
 *   new \Drupal\Core\Asset\LibraryDiscoveryParser($root, $mh, $tm, $swm, $ldf, $epr, $cpm);
 *   new \Drupal\Core\Routing\YamlRouteDiscovery($moduleHandler, $controllerResolver);
 *
 * After:
 *   new \Drupal\Core\Asset\LibraryDiscoveryParser($root, $mh, $tm, $swm, $ldf, $epr, $cpm, \Drupal::service('library.parsing_cache'));
 *   new \Drupal\Core\Routing\YamlRouteDiscovery($moduleHandler, $controllerResolver, \Drupal::service('routing.yaml_cache_collector'));
 *
 * Caveats:
 *   Subclasses of either class whose __construct() calls
 *   parent::__construct() with the old arg count are not rewritten —
 *   those require manual review to determine the correct
 *   $yamlCacheCollector value in the subclass context. Named-argument
 *   call sites where yamlCacheCollector: is already supplied are
 *   correctly skipped.
 *
 * @see https://www.drupal.org/node/3486503
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddYamlCacheCollectorArgRector extends AbstractRector
{
    // Maps FQCN → [expected positional arg count before the new arg, service name to inject]
    private const TARGETS = [
        'Drupal\\Core\\Asset\\LibraryDiscoveryParser' => [7, 'library.parsing_cache'],
        'Drupal\\Core\\Routing\\YamlRouteDiscovery'   => [2, 'routing.yaml_cache_collector'],
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the $yamlCacheCollector argument to new LibraryDiscoveryParser() and new YamlRouteDiscovery() calls deprecated in drupal:11.4.0.',
            [new CodeSample(
                "new \\Drupal\\Core\\Asset\\LibraryDiscoveryParser(\$root, \$mh, \$tm, \$swm, \$ldf, \$epr, \$cpm);",
                "new \\Drupal\\Core\\Asset\\LibraryDiscoveryParser(\$root, \$mh, \$tm, \$swm, \$ldf, \$epr, \$cpm, \\Drupal::service('library.parsing_cache'));",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof New_) {
            return null;
        }

        foreach (self::TARGETS as $fqcn => [$expectedCount, $serviceName]) {
            if (!$this->isName($node->class, $fqcn)) {
                continue;
            }

            // Skip if a named 'yamlCacheCollector' argument is already present.
            foreach ($node->args as $arg) {
                if ($arg instanceof Arg && $arg->name !== null && $arg->name->name === 'yamlCacheCollector') {
                    return null;
                }
            }

            // Only add the argument when the call has exactly the pre-deprecation count.
            if (count($node->args) !== $expectedCount) {
                return null;
            }

            $serviceCall = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new String_($serviceName))],
            );
            $node->args[] = new Arg($serviceCall);
            return $node;
        }

        return null;
    }
}
