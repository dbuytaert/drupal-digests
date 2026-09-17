<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.4 removed the UI installer's profile-selection step, so
 * InstallerTestBase::setUpProfile() is now a no-op that only triggers an
 * E_USER_DEPRECATED notice with no replacement. Contrib and distribution
 * functional tests that extend this base class and replicate the old
 * step-by-step installer sequence (a common pattern in core's own
 * installer tests) call $this->setUpProfile(); as a bare statement. This
 * rule removes that statement, silencing the deprecation notice without
 * changing test behavior, since the step no longer exists.
 *
 * Before:
 *   class MyDistroInstallerTest extends \Drupal\FunctionalTests\Installer\InstallerTestBase {
 *     public function testInstaller(): void {
 *       $this->visitInstaller();
 *       $this->setUpLanguage();
 *       $this->setUpProfile();
 *       $this->setUpRequirementsProblem();
 *       $this->setUpSettings();
 *     }
 *   }
 *
 * After:
 *   class MyDistroInstallerTest extends \Drupal\FunctionalTests\Installer\InstallerTestBase {
 *     public function testInstaller(): void {
 *       $this->visitInstaller();
 *       $this->setUpLanguage();
 *       $this->setUpRequirementsProblem();
 *       $this->setUpSettings();
 *     }
 *   }
 *
 * Caveats:
 *   Only removes bare, no-argument $this->setUpProfile(); statement
 *   calls. It does not touch method overrides of setUpProfile() (e.g. a
 *   subclass that adds assertions and then calls
 *   parent::setUpProfile();), since those mix custom logic that needs
 *   human review; nor calls with arguments or on non-$this receivers,
 *   which never matched the deprecated API shape.
 *
 * @see https://www.drupal.org/node/3520028
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class RemoveDeprecatedInstallerTestSetUpProfileCallRector extends AbstractRector
{
    private const BASE_CLASS = 'Drupal\FunctionalTests\Installer\InstallerTestBase';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove calls to the now-deprecated, no-op InstallerTestBase::setUpProfile().',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$this->visitInstaller();
$this->setUpLanguage();
$this->setUpProfile();
$this->setUpRequirementsProblem();
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$this->visitInstaller();
$this->setUpLanguage();
$this->setUpRequirementsProblem();
CODE_SAMPLE
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /** @param Expression $node */
    public function refactor(Node $node)
    {
        if (!$node instanceof Expression) {
            return null;
        }
        if (!$node->expr instanceof MethodCall) {
            return null;
        }
        $methodCall = $node->expr;
        if (!$this->isName($methodCall->name, 'setUpProfile')) {
            return null;
        }
        if (count($methodCall->args) !== 0) {
            return null;
        }
        if (!$methodCall->var instanceof Variable || !$this->isName($methodCall->var, 'this')) {
            return null;
        }
        if (!$this->isObjectType($methodCall->var, new ObjectType(self::BASE_CLASS))) {
            return null;
        }

        return NodeVisitor::REMOVE_NODE;
    }
}
