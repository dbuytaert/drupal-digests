<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3557135
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// In Drupal 11.3, EntityTypeBundleInfoInterface::getBundleInfo() gained
// a PHP string type hint on its $entity_type_id parameter (previously
// only in the docblock). PHP 8.5 deprecates passing null to non-nullable
// string parameters, so any custom class implementing the interface must
// add the matching type hint to stay compatible.
//
// Before:
//   class MyBundleInfo implements EntityTypeBundleInfoInterface {
//       public function getBundleInfo($entity_type_id) {
//           return [];
//       }
//   }
//
// After:
//   class MyBundleInfo implements EntityTypeBundleInfoInterface {
//       public function getBundleInfo(string $entity_type_id) {
//           return [];
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the `string` type hint to getBundleInfo() in classes implementing
 * EntityTypeBundleInfoInterface.
 *
 * In Drupal 11.3, EntityTypeBundleInfoInterface::getBundleInfo() gained a PHP
 * `string` type hint on $entity_type_id (previously only in the docblock). PHP
 * 8.5 deprecates passing null to non-nullable string parameters. This rule
 * updates custom implementations so they stay in sync with the interface.
 */
final class AddStringTypeToGetBundleInfoRector extends AbstractRector
{
    private const INTERFACE_SHORT = 'EntityTypeBundleInfoInterface';
    private const INTERFACE_FQN   = 'Drupal\\Core\\Entity\\EntityTypeBundleInfoInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add missing string type hint to getBundleInfo($entity_type_id) in classes implementing EntityTypeBundleInfoInterface',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MyBundleInfo implements EntityTypeBundleInfoInterface {
    public function getBundleInfo($entity_type_id) {
        return [];
    }
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
class MyBundleInfo implements EntityTypeBundleInfoInterface {
    public function getBundleInfo(string $entity_type_id) {
        return [];
    }
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->implementsTargetInterface($node)) {
            return null;
        }

        $changed = false;

        foreach ($node->getMethods() as $method) {
            if (!$this->isName($method, 'getBundleInfo')) {
                continue;
            }
            if (count($method->params) !== 1) {
                continue;
            }

            $param = $method->params[0];

            // Already has string type hint — nothing to do.
            if ($param->type instanceof Identifier && $param->type->toString() === 'string') {
                continue;
            }

            $param->type = new Identifier('string');
            $changed = true;
        }

        return $changed ? $node : null;
    }

    private function implementsTargetInterface(Class_ $class): bool
    {
        foreach ($class->implements as $implement) {
            $name = $implement->toString();
            if ($name === self::INTERFACE_SHORT || $name === self::INTERFACE_FQN) {
                return true;
            }
        }
        return false;
    }
}
