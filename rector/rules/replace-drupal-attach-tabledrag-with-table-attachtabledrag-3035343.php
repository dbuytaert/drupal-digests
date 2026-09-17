declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites calls to the deprecated global function
 * drupal_attach_tabledrag() into calls to the new
 * \Drupal\Core\Render\Element\Table::attachTabledrag() static method,
 * which now holds the logic. The function is deprecated in drupal:11.5.0
 * and will be removed in drupal:13.0.0. Only two-argument calls
 * ($element, $options) are rewritten; the argument list is passed
 * through unchanged, preserving named arguments and by-reference
 * semantics.
 *
 * Before:
 *   $element = [];
 *   $options = [
 *     'table_id' => 'my-module-table',
 *     'action' => 'order',
 *     'relationship' => 'sibling',
 *     'group' => 'my-elements-weight',
 *   ];
 *   drupal_attach_tabledrag($element, $options);
 *
 * After:
 *   $element = [];
 *   $options = [
 *     'table_id' => 'my-module-table',
 *     'action' => 'order',
 *     'relationship' => 'sibling',
 *     'group' => 'my-elements-weight',
 *   ];
 *   \Drupal\Core\Render\Element\Table::attachTabledrag($element, $options);
 *
 * Caveats:
 *   Only rewrites calls with exactly two arguments, matching the
 *   function's fixed (&$element, array $options) signature; calls with
 *   a different arity (which would already be fatal errors against the
 *   real function) are left untouched.
 *
 * @see https://www.drupal.org/node/3035343
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceDrupalAttachTabledragRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace drupal_attach_tabledrag() with \Drupal\Core\Render\Element\Table::attachTabledrag().',
            [new CodeSample(
                'drupal_attach_tabledrag($element, $options);',
                '\Drupal\Core\Render\Element\Table::attachTabledrag($element, $options);',
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
        if (!$this->isName($node, 'drupal_attach_tabledrag')) {
            return null;
        }
        if (count($node->args) !== 2) {
            return null;
        }

        return new StaticCall(
            new FullyQualified('Drupal\\Core\\Render\\Element\\Table'),
            'attachTabledrag',
            $node->args,
        );
    }
}
