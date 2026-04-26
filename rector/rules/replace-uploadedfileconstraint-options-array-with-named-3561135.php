<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3561135
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Symfony validator 7.4 deprecated passing an associative array as the
// first argument to Constraint constructors in favour of named
// arguments. Drupal's UploadedFileConstraint follows suit (issue
// #3561135). This rule rewrites every call site that still uses the old
// ['maxSize' => …] options array so it uses explicit named parameters
// instead, eliminating the deprecation warning.
//
// Before:
//   new UploadedFileConstraint(['maxSize' => 1024000, 'groups' => ['myGroup']]);
//
// After:
//   new UploadedFileConstraint(maxSize: 1024000, groups: ['myGroup']);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites the deprecated options-array style of instantiating
 * UploadedFileConstraint to explicit named constructor arguments.
 *
 * Symfony validator 7.4 deprecated passing an associative array as the first
 * argument to Constraint constructors. Drupal's UploadedFileConstraint follows
 * suit: callers must switch from the old options-array form to named args.
 *
 * @see https://www.drupal.org/project/drupal/issues/3561135
 */
final class UploadedFileConstraintArrayOptionsToNamedArgsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated options-array argument of UploadedFileConstraint with named constructor arguments.',
            [
                new CodeSample(
                    "new UploadedFileConstraint(['maxSize' => 1024000]);",
                    'new UploadedFileConstraint(maxSize: 1024000);'
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /**
     * @param New_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Match both short (imported) and fully-qualified class name.
        if (! $this->isNames($node->class, [
            'UploadedFileConstraint',
            'Drupal\\file\\Validation\\Constraint\\UploadedFileConstraint',
        ])) {
            return null;
        }

        // Nothing to do when there are no arguments at all.
        if (count($node->args) === 0) {
            return null;
        }

        $firstArg = $node->args[0];

        // Skip when the first argument is already a named arg, or is not a
        // plain Arg node (e.g. variadic/unpack).
        if (! $firstArg instanceof Arg || $firstArg->name !== null) {
            return null;
        }

        // The first argument must be an inline array literal with string keys.
        if (! $firstArg->value instanceof Array_) {
            return null;
        }

        $array = $firstArg->value;

        // Bail out if any item lacks a string-literal key – we cannot safely
        // produce a named argument in that case.
        foreach ($array->items as $item) {
            if ($item === null || ! $item->key instanceof String_) {
                return null;
            }
        }

        // Convert every array item into an explicit named argument.
        $namedArgs = [];
        foreach ($array->items as $item) {
            /** @var \PhpParser\Node\Expr\ArrayItem $item */
            $namedArg = new Arg($item->value);
            $namedArg->name = new Identifier($item->key->value);
            $namedArgs[] = $namedArg;
        }

        // Replace the options-array arg with the named args; preserve any
        // additional positional args (groups, payload, …) that may follow.
        $remainingArgs = array_slice($node->args, 1);
        $node->args = array_merge($namedArgs, $remainingArgs);

        return $node;
    }
}
