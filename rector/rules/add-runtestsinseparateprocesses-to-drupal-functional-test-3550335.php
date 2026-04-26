<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3550335
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds the #[RunTestsInSeparateProcesses] PHP attribute to every
// concrete (non-abstract) Functional and FunctionalJavascript test class
// that is missing it. Since PHPUnit 12, process isolation can no longer
// be configured programmatically in the test constructor.
// BrowserTestBase::setUp() throws a deprecation when a concrete subclass
// lacks this attribute, and the attribute cannot be inherited from
// abstract base classes.
//
// Before:
//   use Drupal\Tests\BrowserTestBase;
//   use PHPUnit\Framework\Attributes\Group;
//   
//   #[Group('my_module')]
//   class MyModuleTest extends BrowserTestBase {
//     protected $defaultTheme = 'stark';
//   }
//
// After:
//   use Drupal\Tests\BrowserTestBase;
//   use PHPUnit\Framework\Attributes\Group;
//   
//   #[Group('my_module')]
//   #[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
//   class MyModuleTest extends BrowserTestBase {
//     protected $defaultTheme = 'stark';
//   }


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds #[RunTestsInSeparateProcesses] to concrete Drupal Functional test classes.
 *
 * Since PHPUnit 12, process isolation can no longer be set programmatically in
 * the test constructor or setUp(). Drupal's BrowserTestBase::setUp() throws a
 * deprecation (and will eventually throw an exception) when a concrete
 * Functional or FunctionalJavascript test class is missing this attribute.
 * The attribute cannot be placed on abstract base classes - it must appear on
 * every concrete (non-abstract) subclass.
 */
final class AddRunTestsInSeparateProcessesAttributeRector extends AbstractRector
{
    private const ATTRIBUTE_CLASS = 'PHPUnit\\Framework\\Attributes\\RunTestsInSeparateProcesses';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[RunTestsInSeparateProcesses] to concrete Drupal Functional/FunctionalJavascript test classes',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;

#[Group('my_module')]
class MyModuleTest extends BrowserTestBase {
}
CODE_SAMPLE,
                    <<<'CODE_SAMPLE'
use Drupal\Tests\BrowserTestBase;
use PHPUnit\Framework\Attributes\Group;

#[Group('my_module')]
#[\PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses]
class MyModuleTest extends BrowserTestBase {
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Class_);

        // Only non-abstract classes need the attribute.
        if ($node->isAbstract()) {
            return null;
        }

        // Must extend something (test classes always have a parent).
        if ($node->extends === null) {
            return null;
        }

        // Class name must end with "Test".
        $className = $node->name?->toString();
        if ($className === null || !str_ends_with($className, 'Test')) {
            return null;
        }

        // Limit to Drupal Functional / FunctionalJavascript test directories.
        $filePath = $this->getFile()->getFilePath();
        $isFunctional = str_contains($filePath, '/Functional/')
            || str_contains($filePath, '/FunctionalJavascript/')
            || str_contains($filePath, DIRECTORY_SEPARATOR . 'Functional' . DIRECTORY_SEPARATOR)
            || str_contains($filePath, DIRECTORY_SEPARATOR . 'FunctionalJavascript' . DIRECTORY_SEPARATOR);

        if (!$isFunctional) {
            return null;
        }

        // Skip if the attribute is already present.
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attrName = $attr->name->toString();
                if ($attrName === self::ATTRIBUTE_CLASS
                    || $attrName === 'RunTestsInSeparateProcesses'
                ) {
                    return null;
                }
            }
        }

        // Append the attribute group.
        $attribute = new Attribute(new FullyQualified(self::ATTRIBUTE_CLASS));
        $node->attrGroups[] = new AttributeGroup([$attribute]);

        return $node;
    }
}
