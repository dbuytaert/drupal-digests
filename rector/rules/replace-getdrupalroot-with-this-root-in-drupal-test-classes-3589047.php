<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces calls to the deprecated DrupalTestCaseTrait::getDrupalRoot()
 * method with direct access to the $this->root property. The method was
 * deprecated in drupal:12.0.0 and will be removed in drupal:13.0.0. The
 * rule targets subclasses of BrowserTestBase, KernelTestBase, and
 * UnitTestCase, which all inherit $root from DrupalTestCaseTrait and do
 * not override the deprecated method.
 *
 * Before:
 *   $dir = $this->getDrupalRoot() . '/core/tests/fixtures';
 *
 * After:
 *   $dir = $this->root . '/core/tests/fixtures';
 *
 * Caveats:
 *   Only rewrites instance method calls ($this->getDrupalRoot()).
 *   Static calls (static::getDrupalRoot()) in data providers, as shown
 *   in the original MR, require structural changes (e.g., introducing a
 *   placeholder string and resolving it at runtime) that fall outside
 *   the scope of a simple Rector rewrite.
 *   BuildTestBase::getDrupalRoot() is intentionally excluded because it
 *   overrides the trait method with a different non-deprecated
 *   implementation.
 *
 * @see https://www.drupal.org/node/3589047
 * @deprecated drupal:12.0.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class GetDrupalRootToRootPropertyRector extends AbstractRector
{
    // Base test classes that use DrupalTestCaseTrait and do NOT override getDrupalRoot().
    private const BASE_TEST_CLASSES = [
        'Drupal\\Tests\\BrowserTestBase',
        'Drupal\\KernelTests\\KernelTestBase',
        'Drupal\\Tests\\UnitTestCase',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated DrupalTestCaseTrait::getDrupalRoot() calls with $this->root property access.',
            [new CodeSample(
                '$dir = $this->getDrupalRoot() . \'/core/tests/fixtures\';',
                '$dir = $this->root . \'/core/tests/fixtures\';',
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($node->name, 'getDrupalRoot')) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }
        $isTestBase = false;
        foreach (self::BASE_TEST_CLASSES as $fqcn) {
            if ($this->isObjectType($node->var, new ObjectType($fqcn))) {
                $isTestBase = true;
                break;
            }
        }
        if (!$isTestBase) {
            return null;
        }
        return new PropertyFetch($node->var, new Identifier('root'));
    }
}
