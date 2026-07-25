<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal\Tests\Core\Database\Stub\StubPDO is deprecated with no
 * replacement: it existed only because older PHPUnit could not mock \PDO
 * directly, so core wrapped it in a no-op subclass. Now that PHPUnit can
 * stub \PDO itself, tests that pass StubPDO::class to
 * createStub()/createMock()/getMockBuilder() should reference
 * \PDO::class directly. This rule rewrites the ::class constant fetch
 * only, since StubPDO's constructor is a no-op while \PDO's is not:
 * rewriting new StubPDO(), extends StubPDO, or instanceof StubPDO would
 * change runtime behavior, so those forms are intentionally left
 * untouched.
 *
 * Before:
 *   $mock_pdo = $this->createStub(\Drupal\Tests\Core\Database\Stub\StubPDO::class);
 *   $connection = new StubConnection($mock_pdo, []);
 *
 * After:
 *   $mock_pdo = $this->createStub(\PDO::class);
 *   $connection = new StubConnection($mock_pdo, []);
 *
 * Caveats:
 *   Only rewrites the ::class constant fetch. Direct instantiation (new
 *   StubPDO()), extends StubPDO, and instanceof StubPDO are left
 *   untouched because StubPDO's constructor is a no-op while \PDO's
 *   real constructor requires connection arguments; blindly renaming
 *   those forms could produce a fatal error. Leftover unused use
 *   Drupal\Tests\Core\Database\Stub\StubPDO; imports are not removed.
 *
 * @see https://www.drupal.org/node/3585476
 * @deprecated drupal:11.5.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceStubPdoClassConstantRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace \Drupal\Tests\Core\Database\Stub\StubPDO::class with \PDO::class.',
            [new CodeSample(
                '$mock = $this->createStub(\Drupal\Tests\Core\Database\Stub\StubPDO::class);',
                '$mock = $this->createStub(\PDO::class);',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    /** @param ClassConstFetch $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof ClassConstFetch) {
            return null;
        }
        if (!$this->isName($node->name, 'class')) {
            return null;
        }
        if (!$node->class instanceof Node\Name) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\\Tests\\Core\\Database\\Stub\\StubPDO')) {
            return null;
        }

        return new ClassConstFetch(new FullyQualified('PDO'), 'class');
    }
}
