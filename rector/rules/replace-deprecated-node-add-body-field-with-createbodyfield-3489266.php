<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites standalone calls to the deprecated node_add_body_field($type)
 * (and the two-argument form node_add_body_field($type, $label)) to
 * $this->createBodyField('node', $type->id()). The function was
 * deprecated in Drupal 11.3.0 and removed in 12.0.0 with no core-level
 * replacement; test classes should use BodyFieldCreationTrait from
 * Drupal\Tests\field\Traits to supply the method.
 *
 * Before:
 *   node_add_body_field($type);
 *
 * After:
 *   $this->createBodyField('node', $type->id());
 *
 * Caveats:
 *   Only fires when the call is a standalone statement (return value
 *   discarded). Fluent-chain uses such as
 *   node_add_body_field($type)->setLabel(...) are intentionally skipped
 *   because createBodyField() returns void. Named arguments are also
 *   skipped. The calling class must use BodyFieldCreationTrait (or
 *   define an equivalent createBodyField() method) for the rewritten
 *   code to work.
 *
 * @see https://www.drupal.org/node/3489266
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class DeprecateNodeAddBodyFieldRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_add_body_field() with BodyFieldCreationTrait::createBodyField().',
            [new CodeSample(
                'node_add_body_field($type);',
                "\$this->createBodyField('node', \$type->id());",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        // Match the Expression statement wrapper to guarantee the return value
        // is discarded; this prevents replacing chained calls where
        // createBodyField() returns void.
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Expression) {
            return null;
        }
        if (!$node->expr instanceof FuncCall) {
            return null;
        }
        $funcCall = $node->expr;
        if (!$this->isName($funcCall->name, 'node_add_body_field')) {
            return null;
        }
        if (count($funcCall->args) < 1 || count($funcCall->args) > 2) {
            return null;
        }
        // Skip named arguments.
        foreach ($funcCall->args as $arg) {
            if ($arg instanceof Arg && $arg->name !== null) {
                return null;
            }
        }

        $typeArg = $funcCall->args[0]->value;
        // $type->id() produces the bundle machine name required by createBodyField().
        $typeIdCall = new MethodCall($typeArg, 'id');

        $args = [
            new Arg(new String_('node')),
            new Arg($typeIdCall),
        ];

        if (count($funcCall->args) === 2) {
            // Forward the label as the $fieldLabel (4th) parameter; 'body' is
            // the default $fieldName (3rd) and is passed explicitly to reach
            // the 4th positional parameter.
            $args[] = new Arg(new String_('body'));
            $args[] = new Arg($funcCall->args[1]->value);
        }

        $node->expr = new MethodCall(new Variable('this'), 'createBodyField', $args);
        return $node;
    }
}
