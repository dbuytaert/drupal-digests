<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3311365
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Reads a module's *.routing.yml file and adds #[Route] attributes to
// the corresponding controller methods. Since Drupal 11.x,
// AttributeRouteDiscovery can discover routes from Symfony's #[Route]
// attribute on methods in src/Controller/, co-locating route definitions
// with their handler. Methods that already carry a Route attribute are
// skipped. The defaults._controller key is implicit and not reproduced
// in the attribute.
//
// Before:
//   // mymodule.routing.yml:
//   // mymodule.hello:
//   //   path: '/hello'
//   //   defaults:
//   //     _controller: '\Drupal\mymodule\Controller\HelloController::hello'
//   //     _title: 'Hello World'
//   //   requirements:
//   //     _permission: 'access content'
//   
//   public function hello() {
//       return ['#markup' => $this->t('Hello World')];
//   }
//
// After:
//   #[\Symfony\Component\Routing\Attribute\Route(
//       path: '/hello',
//       name: 'mymodule.hello',
//       requirements: ['_permission' => 'access content'],
//       defaults: ['_title' => 'Hello World'],
//   )]
//   public function hello() {
//       return ['#markup' => $this->t('Hello World')];
//   }


use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symfony\Component\Yaml\Yaml;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds Symfony #[Route] attributes to Drupal controller methods based on the
 * module's routing YAML definitions.
 *
 * Configure with a list of *.routing.yml file paths. For each route whose
 * defaults._controller points to a class::method pair found in a processed
 * PHP class, a #[Route] attribute is prepended to that method. The
 * _controller default is implicit and is not included in the attribute;
 * all other YAML route properties (path, name, requirements, defaults,
 * methods, schemes, host, options) are mapped to named attribute arguments.
 * Methods that already carry a Route attribute are left unchanged.
 *
 * Usage example in rector.php:
 *
 *   ->withConfiguredRule(YamlRoutesToRouteAttributeRector::class, [
 *       __DIR__ . '/web/modules/custom/mymodule/mymodule.routing.yml',
 *   ])
 */
final class YamlRoutesToRouteAttributeRector extends AbstractRector
{
    /** @var array<string, array<string, mixed>> Keyed by "FQCN::method". */
    private array $routesByController = [];

    /** @var array<string, array<string, mixed>> Cache: module dir => parsed routes */
    private array $parsedDirs = [];

    /** @var array<string, list<string>> YAML file path => list of converted route names */
    private array $convertedRoutes = [];

    private function discoverRoutes(string $filePath): void
    {
        $dir = dirname($filePath);
        // Walk up to find module root (directory with *.info.yml)
        while ($dir !== dirname($dir)) {
            $infoFiles = glob($dir . '/*.info.yml');
            if ($infoFiles) {
                break;
            }
            $dir = dirname($dir);
        }

        if (isset($this->parsedDirs[$dir])) {
            return;
        }
        $this->parsedDirs[$dir] = true;

        // Find all *.routing.yml files in the module root
        $routingFiles = glob($dir . '/*.routing.yml');
        foreach ($routingFiles as $yamlPath) {
            $content = file_get_contents($yamlPath);
            if ($content === false) {
                continue;
            }
            $routes = Yaml::parse($content);
            if (!is_array($routes)) {
                continue;
            }
            foreach ($routes as $routeName => $routeConfig) {
                if (!is_array($routeConfig)) {
                    continue;
                }
                $controller = $routeConfig['defaults']['_controller'] ?? null;
                if (!is_string($controller)) {
                    continue;
                }
                $controller = ltrim($controller, '\\');
                if (str_contains($controller, ':') && !str_contains($controller, '::')) {
                    $controller = str_replace(':', '::', $controller);
                }
                $routeConfig['_route_name'] = $routeName;
                $routeConfig['_yaml_file'] = $yamlPath;
                $this->routesByController[$controller] = $routeConfig;
            }
        }
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[Route] attributes to Drupal controller methods from routing YAML files',
            [
                new CodeSample(
                    'public function hello() { return [\'#markup\' => \'Hi\']; }',
                    '#[\\Symfony\\Component\\Routing\\Attribute\\Route(path: \'/hello\', name: \'mymodule.hello\')]' . "\n" .
                    'public function hello() { return [\'#markup\' => \'Hi\']; }'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        $this->discoverRoutes($this->getFile()->getFilePath());

        $className = $node->namespacedName?->toString();
        if ($className === null) {
            return null;
        }

        $changed = false;

        foreach ($node->getMethods() as $method) {
            $methodName = $this->getName($method);
            $key = $className . '::' . $methodName;

            if (!isset($this->routesByController[$key])) {
                continue;
            }

            // Skip if the method already has a Route attribute.
            if ($this->methodHasRouteAttribute($method)) {
                continue;
            }

            $routeConfig = $this->routesByController[$key];
            $attrText = $this->buildRouteAttributeText($routeConfig);

            $comments = $method->getComments();
            $comments[] = new \PhpParser\Comment($attrText);
            $method->setAttribute('comments', $comments);

            $yamlFile = $routeConfig['_yaml_file'];
            $this->convertedRoutes[$yamlFile][] = $routeConfig['_route_name'];
            $changed = true;
        }

        return $changed ? $node : null;
    }

    private function methodHasRouteAttribute(\PhpParser\Node\Stmt\ClassMethod $method): bool
    {
        foreach ($method->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $attr->name->toString();
                if ($name === 'Route' || str_ends_with($name, '\\Route')) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param array<string, mixed> $routeConfig
     * @return Arg[]
     */
    private function buildRouteAttributeText(array $routeConfig): string
    {
        $parts = [];

        if (isset($routeConfig['path'])) {
            $parts[] = "  path: '" . addcslashes($routeConfig['path'], "'") . "'";
        }
        if (isset($routeConfig['_route_name'])) {
            $parts[] = "  name: '" . addcslashes($routeConfig['_route_name'], "'") . "'";
        }
        if (!empty($routeConfig['requirements'])) {
            $parts[] = '  requirements: ' . $this->formatArray($routeConfig['requirements']);
        }
        $defaults = array_filter(
            (array) ($routeConfig['defaults'] ?? []),
            static fn($k) => $k !== '_controller',
            ARRAY_FILTER_USE_KEY
        );
        if (!empty($defaults)) {
            $parts[] = '  defaults: ' . $this->formatArray($defaults);
        }
        if (!empty($routeConfig['methods'])) {
            $parts[] = '  methods: ' . $this->formatList((array) $routeConfig['methods']);
        }
        if (!empty($routeConfig['schemes'])) {
            $parts[] = '  schemes: ' . $this->formatList((array) $routeConfig['schemes']);
        }
        if (!empty($routeConfig['host'])) {
            $parts[] = "  host: '" . addcslashes($routeConfig['host'], "'") . "'";
        }
        if (!empty($routeConfig['options'])) {
            $parts[] = '  options: ' . $this->formatArray($routeConfig['options']);
        }

        return "#[\\Symfony\\Component\\Routing\\Attribute\\Route(\n"
            . implode(",\n", $parts) . ",\n"
            . ')]';
    }

    private function formatArray(array $data): string
    {
        $pairs = [];
        foreach ($data as $k => $v) {
            $pairs[] = "'" . addcslashes((string) $k, "'") . "' => '" . addcslashes((string) $v, "'") . "'";
        }
        return '[' . implode(', ', $pairs) . ']';
    }

    private function formatList(array $items): string
    {
        $quoted = array_map(static fn($s) => "'" . addcslashes((string) $s, "'") . "'", $items);
        return '[' . implode(', ', $quoted) . ']';
    }
    public function __destruct()
    {
        foreach ($this->convertedRoutes as $yamlFile => $routeNames) {
            if (!file_exists($yamlFile)) {
                continue;
            }
            $content = file_get_contents($yamlFile);
            $routes = Yaml::parse($content);
            if (!is_array($routes)) {
                continue;
            }
            foreach ($routeNames as $name) {
                unset($routes[$name]);
            }
            if (empty($routes)) {
                unlink($yamlFile);
            } else {
                file_put_contents($yamlFile, Yaml::dump($routes, 4, 2));
            }
        }
    }
}
