<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3546029
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Since PHPUnit 12 no longer allows process isolation to be set
// programmatically, every concrete class extending KernelTestBase or
// BrowserTestBase must carry the #[RunTestsInSeparateProcesses]
// attribute. Omitting it causes tests to run without isolation and
// triggers an exception in Drupal 11.3+. This rule adds the attribute
// and the corresponding use import automatically to any concrete test
// class that is missing it.
//
// Before:
//   use Drupal\KernelTests\KernelTestBase;
//   use PHPUnit\Framework\Attributes\Group;
//   
//   #[Group('mymodule')]
//   class MyKernelTest extends KernelTestBase {
//   }
//
// After:
//   use Drupal\KernelTests\KernelTestBase;
//   use PHPUnit\Framework\Attributes\Group;
//   use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
//   
//   #[RunTestsInSeparateProcesses]
//   #[Group('mymodule')]
//   class MyKernelTest extends KernelTestBase {
//   }


use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\PostRector\Collector\UseNodesToAddCollector;
use Rector\Rector\AbstractRector;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddRunTestsInSeparateProcessesAttributeRector extends AbstractRector
{
    private const ATTRIBUTE_CLASS = 'PHPUnit\\Framework\\Attributes\\RunTestsInSeparateProcesses';
    private const ATTRIBUTE_SHORT  = 'RunTestsInSeparateProcesses';

    private const TEST_BASE_CLASSES = [
        'Drupal\\KernelTests\\KernelTestBase',
        'Drupal\\Tests\\BrowserTestBase',
    ];

    /**
     * @readonly
     */
    private FamilyRelationsAnalyzer $familyRelationsAnalyzer;

    /**
     * @readonly
     */
    private UseNodesToAddCollector $useNodesToAddCollector;

    public function __construct(
        FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        UseNodesToAddCollector $useNodesToAddCollector
    ) {
        $this->familyRelationsAnalyzer = $familyRelationsAnalyzer;
        $this->useNodesToAddCollector = $useNodesToAddCollector;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[RunTestsInSeparateProcesses] attribute to concrete Drupal Kernel and Functional test classes',
            [
                new CodeSample(
                    <<<'CODE'
use Drupal\KernelTests\KernelTestBase;

class MyKernelTest extends KernelTestBase {
}
CODE,
                    <<<'CODE'
use Drupal\KernelTests\KernelTestBase;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class MyKernelTest extends KernelTestBase {
}
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        // Skip abstract classes – the attribute only works on concrete test classes.
        if ($node->isAbstract()) {
            return null;
        }

        // Only handle classes that extend one of the Drupal test base classes.
        if (!$this->extendsTestBase($node)) {
            return null;
        }

        // Already has the attribute – nothing to do.
        if ($this->hasRunTestsInSeparateProcesses($node)) {
            return null;
        }

        // Register the use import so the PostRector adds it to the file.
        $this->useNodesToAddCollector->addUseImport(
            new FullyQualifiedObjectType(self::ATTRIBUTE_CLASS)
        );

        // Build #[RunTestsInSeparateProcesses] using the short name (use import covers it).
        $attribute = new Attribute(new Name(self::ATTRIBUTE_SHORT));
        $attributeGroup = new AttributeGroup([$attribute]);
        array_unshift($node->attrGroups, $attributeGroup);

        return $node;
    }

    private function extendsTestBase(Class_ $class): bool
    {
        $ancestors = $this->familyRelationsAnalyzer->getClassLikeAncestorNames($class);

        foreach (self::TEST_BASE_CLASSES as $baseClass) {
            if (in_array($baseClass, $ancestors, true)) {
                return true;
            }
        }

        return false;
    }

    private function hasRunTestsInSeparateProcesses(Class_ $class): bool
    {
        foreach ($class->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $name = $attr->name->toString();
                if ($name === self::ATTRIBUTE_SHORT || $name === self::ATTRIBUTE_CLASS) {
                    return true;
                }
            }
        }

        return false;
    }
}
