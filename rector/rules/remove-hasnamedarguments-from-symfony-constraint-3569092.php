<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3569092
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Removes the #[HasNamedArguments] attribute (and its fully-qualified
// form #[\Symfony\Component\Validator\Constraints\HasNamedArguments])
// from __construct methods. The attribute was deprecated in Symfony 7.4
// and removed in Symfony 8, which Drupal 11.x now requires. Leaving the
// attribute in place is dead code; Drupal core removed all usages in
// issue #3569092.
//
// Before:
//   use Symfony\Component\Validator\Constraint;
//   use Symfony\Component\Validator\Constraints\HasNamedArguments;
//   
//   class MyConstraint extends Constraint {
//       #[HasNamedArguments]
//       public function __construct(
//           public string $myOption = '',
//           mixed $options = null,
//           ?array $groups = null,
//           mixed $payload = null,
//       ) {
//           parent::__construct($options ?? [], $groups, $payload);
//       }
//   }
//
// After:
//   use Symfony\Component\Validator\Constraint;
//   
//   class MyConstraint extends Constraint {
//       public function __construct(
//           public string $myOption = '',
//           mixed $options = null,
//           ?array $groups = null,
//           mixed $payload = null,
//       ) {
//           parent::__construct($options ?? [], $groups, $payload);
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the #[HasNamedArguments] attribute from Symfony Constraint
 * constructors. The attribute was deprecated in Symfony 7.4 and removed
 * in Symfony 8. Drupal 11.x requires Symfony 8.
 */
final class RemoveHasNamedArgumentsAttributeRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove #[HasNamedArguments] from Symfony Constraint constructors; the attribute was removed in Symfony 8',
            [
                new CodeSample(
                    <<<'CODE_BEFORE'
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\HasNamedArguments;

class MyConstraint extends Constraint {
    #[HasNamedArguments]
    public function __construct(
        public string $myOption = '',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }
}
CODE_BEFORE
                    ,
                    <<<'CODE_AFTER'
use Symfony\Component\Validator\Constraint;

class MyConstraint extends Constraint {
    public function __construct(
        public string $myOption = '',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options ?? [], $groups, $payload);
    }
}
CODE_AFTER
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /** @param ClassMethod $node */
    public function refactor(Node $node): ?Node
    {
        // Only target __construct methods.
        if (!$this->isName($node->name, '__construct')) {
            return null;
        }

        $changed = false;
        $newAttrGroups = [];

        foreach ($node->attrGroups as $attrGroup) {
            $filteredAttrs = [];
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $this->getName($attr->name);
                if (
                    $attrName === 'Symfony\\Component\\Validator\\Constraints\\HasNamedArguments'
                    || $attrName === 'HasNamedArguments'
                ) {
                    $changed = true;
                    continue;
                }
                $filteredAttrs[] = $attr;
            }

            if ($filteredAttrs !== []) {
                $newAttrGroups[] = new AttributeGroup($filteredAttrs);
            } elseif (count($attrGroup->attrs) > count($filteredAttrs)) {
                $changed = true;
            }
        }

        if (!$changed) {
            return null;
        }

        $node->attrGroups = $newAttrGroups;
        return $node;
    }
}
