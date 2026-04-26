<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3578694
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites closure and arrow-function parameter type hints from the
// concrete Symfony\Component\Validator\ConstraintViolation class to the
// ConstraintViolationInterface interface. Using the concrete class
// causes PHPStan type errors when the closure is passed to array_map or
// similar functions that iterate a ConstraintViolationList, because the
// list's type contract is the interface, not the implementation. Covers
// both the short imported name and the fully-qualified form.
//
// Before:
//   use Symfony\Component\Validator\ConstraintViolation;
//   
//   $messages = array_map(
//       function (ConstraintViolation $v) {
//           return $v->getMessage();
//       },
//       iterator_to_array($violations)
//   );
//
// After:
//   use Symfony\Component\Validator\ConstraintViolationInterface;
//   
//   $messages = array_map(
//       function (ConstraintViolationInterface $v) {
//           return $v->getMessage();
//       },
//       iterator_to_array($violations)
//   );


use PhpParser\Node;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces ConstraintViolation parameter type hints with
 * ConstraintViolationInterface in closures and arrow functions.
 *
 * Symfony's ConstraintViolationInterface is the correct contract to depend on
 * in callbacks (e.g. array_map over a ConstraintViolationList). Using the
 * concrete ConstraintViolation class causes PHPStan type errors because
 * array_map expects a callable typed against the interface, not the
 * implementation. The rewrite covers both the short imported name and the
 * fully-qualified form.
 */
final class ConstraintViolationToInterfaceRector extends AbstractRector
{
    private const CONCRETE_CLASS  = 'Symfony\\Component\\Validator\\ConstraintViolation';
    private const INTERFACE_CLASS = 'Symfony\\Component\\Validator\\ConstraintViolationInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace ConstraintViolation parameter type hints with ConstraintViolationInterface in closures and arrow functions',
            [
                new CodeSample(
                    'array_map(function (ConstraintViolation $v) { return $v->getMessage(); }, $list);',
                    'array_map(function (ConstraintViolationInterface $v) { return $v->getMessage(); }, $list);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Closure::class, ArrowFunction::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Closure && !$node instanceof ArrowFunction) {
            return null;
        }

        $changed = false;

        foreach ($node->params as $param) {
            if (!$param instanceof Param) {
                continue;
            }

            $type = $param->type;
            if (!$type instanceof Name) {
                continue;
            }

            $resolvedName = $type->toString();

            // Match the short imported name and the FQCN (with or without
            // leading backslash - Name::toString() always omits the backslash).
            if (
                $resolvedName !== 'ConstraintViolation'
                && $resolvedName !== self::CONCRETE_CLASS
            ) {
                continue;
            }

            $param->type = new FullyQualified(self::INTERFACE_CLASS);
            $changed = true;
        }

        return $changed ? $node : null;
    }
}
