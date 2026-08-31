<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * user_picture_enabled() is deprecated with no direct replacement;
 * callers must inline the check it used to perform. This rule rewrites
 * zero-argument calls to user_picture_enabled() into isset(\Drupal::serv
 * ice('entity_field.manager')->getFieldDefinitions('user',
 * 'user')['user_picture']), matching exactly what core's own call sites
 * were changed to. It preserves surrounding boolean context (negation,
 * &&, etc.) since the replacement is a drop-in boolean expression.
 *
 * Before:
 *   if (!user_picture_enabled()) {
 *     $disabled['toggle_node_user_picture'] = TRUE;
 *   }
 *   
 *   if (!empty($build['user_picture']) && user_picture_enabled()) {
 *     // ...
 *   }
 *
 * After:
 *   if (!isset(\Drupal::service('entity_field.manager')->getFieldDefinitions('user', 'user')['user_picture'])) {
 *     $disabled['toggle_node_user_picture'] = TRUE;
 *   }
 *   
 *   if (!empty($build['user_picture']) && isset(\Drupal::service('entity_field.manager')->getFieldDefinitions('user', 'user')['user_picture'])) {
 *     // ...
 *   }
 *
 * Caveats:
 *   Only matches calls with zero arguments (the function's real
 *   signature). A user-defined function or method that happens to share
 *   the name user_picture_enabled but takes arguments, or is called as
 *   $this->user_picture_enabled(...), is left untouched since the rule
 *   only targets global FuncCall nodes.
 *
 * @see https://www.drupal.org/node/3151555
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceUserPictureEnabledRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated user_picture_enabled() with an inline check of the user_picture field definition.',
            [new CodeSample(
                'if (!user_picture_enabled()) { }',
                "if (!isset(\\Drupal::service('entity_field.manager')->getFieldDefinitions('user', 'user')['user_picture'])) { }",
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
        if (!$this->isName($node->name, 'user_picture_enabled')) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }

        $service = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('entity_field.manager'))],
        );
        $getFieldDefinitions = new MethodCall(
            $service,
            'getFieldDefinitions',
            [new Arg(new String_('user')), new Arg(new String_('user'))],
        );
        $arrayDimFetch = new ArrayDimFetch($getFieldDefinitions, new String_('user_picture'));

        return new Isset_([$arrayDimFetch]);
    }
}
