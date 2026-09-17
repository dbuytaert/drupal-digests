<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal core deprecated the global function options_allowed_values()
 * and moved its logic into the
 * Drupal\options\OptionsAllowedValuesInterface service
 * (getAllowedValues() method). This rule rewrites direct calls to the
 * old global function into a call on the new service, preserving the
 * original arguments (the field storage definition and optional entity),
 * so contrib and custom code calling it directly keeps working after the
 * function is removed.
 *
 * Before:
 *   $values = options_allowed_values($definition, $entity);
 *   // or without an entity:
 *   $values = options_allowed_values($definition);
 *
 * After:
 *   $values = \Drupal::service(\Drupal\options\OptionsAllowedValuesInterface::class)->getAllowedValues($definition, $entity);
 *   // or without an entity:
 *   $values = \Drupal::service(\Drupal\options\OptionsAllowedValuesInterface::class)->getAllowedValues($definition);
 *
 * Caveats:
 *   Only rewrites calls with 1 or 2 positional arguments (the
 *   documented signature of options_allowed_values()); calls with other
 *   arg counts, named arguments, or first-class callable syntax
 *   (options_allowed_values(...)) are left untouched for manual review.
 *   Does not inject the service via dependency injection; it always
 *   uses \Drupal::service(), which is safe everywhere but not idiomatic
 *   inside injectable classes such as plugins or services (manual
 *   follow-up recommended there).
 *
 * @see https://www.drupal.org/node/2171397
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceOptionsAllowedValuesFunctionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace options_allowed_values() with the options.allowed_values service.',
            [new CodeSample(
                '$values = options_allowed_values($definition, $entity);',
                "\$values = \\Drupal::service(\\Drupal\\options\\OptionsAllowedValuesInterface::class)->getAllowedValues(\$definition, \$entity);",
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
        if ($node->isFirstClassCallable()) {
            return null;
        }
        if (!$this->isName($node->name, 'options_allowed_values')) {
            return null;
        }
        if (count($node->args) < 1 || count($node->args) > 2) {
            return null;
        }

        $interfaceClassConst = new ClassConstFetch(
            new FullyQualified('Drupal\options\OptionsAllowedValuesInterface'),
            'class',
        );

        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($interfaceClassConst)],
        );

        return new MethodCall($service, 'getAllowedValues', $node->args);
    }
}
