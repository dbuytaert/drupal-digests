<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3568087
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal core issue #3568087 removed the procedural functions
// contextual_links_to_id() and contextual_id_to_links() from
// contextual.module and introduced the ContextualLinksSerializer service
// with equivalent linksToId() and idToLinks() methods. This rule
// rewrites every call site so contrib and custom code does not break on
// upgrade.
//
// Before:
//   $id = contextual_links_to_id($contextual_links);
//   $links = contextual_id_to_links($id);
//
// After:
//   $id = \Drupal::service('Drupal\contextual\ContextualLinksSerializer')->linksToId($contextual_links);
//   $links = \Drupal::service('Drupal\contextual\ContextualLinksSerializer')->idToLinks($id);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated contextual_links_to_id() and contextual_id_to_links()
 * procedural functions (removed from contextual.module in Drupal core issue
 * #3568087) with equivalent calls on the new ContextualLinksSerializer service.
 */
final class ReplaceContextualProceduralFunctionsRector extends AbstractRector
{
    /**
     * Maps the removed procedural function name to the new service method name.
     */
    private const FUNCTION_TO_METHOD_MAP = [
        'contextual_links_to_id' => 'linksToId',
        'contextual_id_to_links' => 'idToLinks',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed contextual_links_to_id() and contextual_id_to_links() procedural functions with ContextualLinksSerializer service calls.',
            [
                new CodeSample(
                    '$id = contextual_links_to_id($contextual_links);',
                    '$id = \\Drupal::service(\'Drupal\\contextual\\ContextualLinksSerializer\')->linksToId($contextual_links);'
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node->name instanceof Name) {
            return null;
        }

        $functionName = $this->getName($node->name);
        if (!isset(self::FUNCTION_TO_METHOD_MAP[$functionName])) {
            return null;
        }

        $methodName = self::FUNCTION_TO_METHOD_MAP[$functionName];

        // Build: \Drupal::service('Drupal\contextual\ContextualLinksSerializer')
        $serviceCall = new StaticCall(
            new Name\FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('Drupal\\contextual\\ContextualLinksSerializer'))]
        );

        // Build: ->linksToId($arg)  or  ->idToLinks($arg)
        return new MethodCall($serviceCall, $methodName, $node->args);
    }
}
