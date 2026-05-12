<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces direct instantiation of the deprecated NodeViewController
 * with EntityViewController, dropping the extra AccountInterface and
 * EntityRepositoryInterface constructor arguments that
 * EntityViewController does not accept. A RenameClassRector pass handles
 * all other references: use imports, type hints, and extends
 * declarations.
 *
 * Before:
 *   use Drupal\node\Controller\NodeViewController;
 *   $ctrl = new NodeViewController($entityTypeManager, $renderer, $currentUser, $entityRepository);
 *   // also: class MyController extends NodeViewController {}
 *
 * After:
 *   use Drupal\Core\Entity\Controller\EntityViewController;
 *   $ctrl = new EntityViewController($entityTypeManager, $renderer);
 *   // also: class MyController extends EntityViewController {}
 *
 * Caveats:
 *   Subclasses that override __construct and call
 *   parent::__construct($a, $b, $c, $d) will have extends updated but
 *   the parent::__construct call is not modified; developers must
 *   manually drop the AccountInterface and EntityRepositoryInterface
 *   arguments from their own parent call.NodeViewController::title()
 *   has no equivalent on EntityViewController; callers of that method
 *   are not rewritten.
 *
 * @see https://www.drupal.org/node/3589630
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceNodeViewControllerRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace new NodeViewController(...) with new EntityViewController(...) and drop the extra constructor arguments.',
            [new CodeSample(
                'new \Drupal\node\Controller\NodeViewController($entityTypeManager, $renderer, $currentUser, $entityRepository)',
                'new \Drupal\Core\Entity\Controller\EntityViewController($entityTypeManager, $renderer)',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof New_) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\node\Controller\NodeViewController')) {
            return null;
        }
        $node->class = new FullyQualified('Drupal\Core\Entity\Controller\EntityViewController');
        // EntityViewController::__construct only takes 2 args; drop any extras.
        $node->args = array_slice($node->args, 0, 2);
        return $node;
    }
}
