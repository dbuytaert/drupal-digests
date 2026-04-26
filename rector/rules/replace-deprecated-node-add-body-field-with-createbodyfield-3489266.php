<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3489266
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated node_add_body_field() function
// (deprecated in drupal:11.3.0, removed in drupal:12.0.0) with the
// equivalent $this->createBodyField('node', $type->id()) method from
// BodyFieldCreationTrait. Handles the optional label argument by passing
// 'body' as the field name and forwarding the label to the trait method.
//
// Before:
//   node_add_body_field($nodeType, 'My Body');
//
// After:
//   $this->createBodyField('node', $nodeType->id(), 'body', 'My Body');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated node_add_body_field() calls with createBodyField().
 *
 * node_add_body_field() is deprecated in drupal:11.3.0 and removed in
 * drupal:12.0.0. In test classes using BodyFieldCreationTrait, the equivalent
 * call is $this->createBodyField('node', $type->id()).
 */
final class NodeAddBodyFieldRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated node_add_body_field() with createBodyField() from BodyFieldCreationTrait.',
            [
                new CodeSample(
                    'node_add_body_field($nodeType);',
                    '$this->createBodyField(\'node\', $nodeType->id());'
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
        if (!$this->isName($node, 'node_add_body_field')) {
            return null;
        }

        $args = $node->args;
        if (empty($args)) {
            return null;
        }

        // First argument: the NodeType object. We call ->id() on it.
        $typeArg = $args[0] instanceof Arg ? $args[0]->value : null;
        if ($typeArg === null) {
            return null;
        }

        // Build: $type->id()
        $idCall = new MethodCall($typeArg, 'id');

        // Build the new argument list for createBodyField('node', $type->id(), ...)
        $newArgs = [
            new Arg(new String_('node')),
            new Arg($idCall),
        ];

        // If a label argument was supplied, pass fieldName='body' and the label.
        if (isset($args[1]) && $args[1] instanceof Arg) {
            $newArgs[] = new Arg(new String_('body'));
            $newArgs[] = new Arg($args[1]->value);
        }

        // Replace with: $this->createBodyField(...)
        return new MethodCall(
            new Variable('this'),
            'createBodyField',
            $newArgs
        );
    }
}
