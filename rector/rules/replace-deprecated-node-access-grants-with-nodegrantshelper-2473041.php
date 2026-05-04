<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the procedural node_access_grants() function,
 * deprecated in drupal:11.4.0, to the equivalent call on the new
 * NodeGrantsHelper service. The function is replaced by \Drupal::service
 * (\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants(), which
 * encapsulates the same hook invocation logic in an injectable class.
 *
 * Before:
 *   node_access_grants($operation, $account);
 *
 * After:
 *   \Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants($operation, $account);
 *
 * Caveats:
 *   Calls with a number of arguments other than two are skipped, as
 *   they do not match the known signature and may represent different
 *   code.
 *
 * @see https://www.drupal.org/node/2473041
 * @deprecated drupal:11.4.0
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

final class ReplaceNodeAccessGrantsFunctionRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace node_access_grants() with \Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants().',
            [new CodeSample(
                'node_access_grants($operation, $account);',
                '\Drupal::service(\Drupal\node\NodeGrantsHelper::class)->nodeAccessGrants($operation, $account);',
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
        if (!$this->isName($node->name, 'node_access_grants')) {
            return null;
        }
        if (count($node->args) !== 2) {
            return null;
        }

        $serviceClassConst = new ClassConstFetch(
            new FullyQualified('Drupal\node\NodeGrantsHelper'),
            'class',
        );
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($serviceClassConst)],
        );

        return new MethodCall($serviceCall, 'nodeAccessGrants', $node->args);
    }
}
