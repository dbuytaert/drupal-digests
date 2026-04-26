<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3574461
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Adds \Drupal\layout_builder\SupportAwareSectionStorageInterface and a
// stub isSupported() method to any section storage plugin that extends
// SectionStorageBase or implements SectionStorageInterface but does not
// yet carry the interface. Not implementing it was deprecated in
// drupal:11.4.0 and is required from drupal:13.0.0. The stub returns
// TRUE, preserving the backward-compatible "always supported" behaviour.
//
// Before:
//   class CustomSectionStorage extends SectionStorageBase {
//     protected function getSectionList() {
//       return $this->sectionList;
//     }
//   }
//
// After:
//   class CustomSectionStorage extends SectionStorageBase implements \Drupal\layout_builder\SupportAwareSectionStorageInterface {
//     protected function getSectionList() {
//       return $this->sectionList;
//     }
//     public function isSupported(string $entity_type_id, string $bundle, string $view_mode): bool {
//       return TRUE;
//     }
//   }


use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds SupportAwareSectionStorageInterface to Layout Builder section storage plugins.
 *
 * Any class that extends SectionStorageBase or directly implements
 * SectionStorageInterface without also implementing
 * SupportAwareSectionStorageInterface triggers a deprecation in drupal:11.4.0.
 * This rule adds the interface to the class declaration and inserts an
 * isSupported() stub that preserves backward-compatible behaviour (returns TRUE).
 */
final class AddSupportAwareSectionStorageInterfaceRector extends AbstractRector
{
    private const SECTION_STORAGE_BASE = 'Drupal\\layout_builder\\Plugin\\SectionStorage\\SectionStorageBase';
    private const SECTION_STORAGE_INTERFACE = 'Drupal\\layout_builder\\SectionStorageInterface';
    private const SUPPORT_AWARE_INTERFACE = 'Drupal\\layout_builder\\SupportAwareSectionStorageInterface';

    private const SHORT_BASE = 'SectionStorageBase';
    private const SHORT_INTERFACE = 'SectionStorageInterface';
    private const SHORT_SUPPORT_AWARE = 'SupportAwareSectionStorageInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add SupportAwareSectionStorageInterface to Layout Builder section storage plugins that are missing it',
            [
                new CodeSample(
                    'class CustomSectionStorage extends SectionStorageBase {}',
                    'class CustomSectionStorage extends SectionStorageBase implements \\Drupal\\layout_builder\\SupportAwareSectionStorageInterface {
  public function isSupported(string $entity_type_id, string $bundle, string $view_mode): bool {
    return TRUE;
  }
}'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Class_) {
            return null;
        }

        // Skip abstract classes; they don't need to implement the interface themselves.
        if ($node->isAbstract()) {
            return null;
        }

        if (!$this->isSectionStorageClass($node)) {
            return null;
        }

        // Skip if already implements SupportAwareSectionStorageInterface.
        if ($this->alreadyImplementsSupportAware($node)) {
            return null;
        }

        // Skip if the class already defines isSupported() (compliant via sub-interface).
        if ($this->hasIsSupportedMethod($node)) {
            return null;
        }

        $node->implements[] = new FullyQualified(self::SUPPORT_AWARE_INTERFACE);
        $node->stmts[] = $this->buildIsSupportedMethod();

        return $node;
    }

    private function isSectionStorageClass(Class_ $node): bool
    {
        if ($node->extends !== null) {
            $extends = $node->extends->toString();
            if (
                $extends === self::SHORT_BASE
                || $extends === self::SECTION_STORAGE_BASE
                || str_ends_with($extends, '\\' . self::SHORT_BASE)
            ) {
                return true;
            }
        }

        foreach ($node->implements as $implement) {
            $name = $implement->toString();
            if (
                $name === self::SHORT_INTERFACE
                || $name === self::SECTION_STORAGE_INTERFACE
                || str_ends_with($name, '\\' . self::SHORT_INTERFACE)
            ) {
                return true;
            }
        }

        return false;
    }

    private function alreadyImplementsSupportAware(Class_ $node): bool
    {
        foreach ($node->implements as $implement) {
            $name = $implement->toString();
            if (
                $name === self::SHORT_SUPPORT_AWARE
                || $name === self::SUPPORT_AWARE_INTERFACE
                || str_ends_with($name, '\\' . self::SHORT_SUPPORT_AWARE)
            ) {
                return true;
            }
        }
        return false;
    }

    private function hasIsSupportedMethod(Class_ $node): bool
    {
        foreach ($node->getMethods() as $method) {
            if ($this->isName($method, 'isSupported')) {
                return true;
            }
        }
        return false;
    }

    private function buildIsSupportedMethod(): ClassMethod
    {
        return new ClassMethod('isSupported', [
            'flags' => Class_::MODIFIER_PUBLIC,
            'params' => [
                new Param(new Node\Expr\Variable('entity_type_id'), null, new Identifier('string')),
                new Param(new Node\Expr\Variable('bundle'), null, new Identifier('string')),
                new Param(new Node\Expr\Variable('view_mode'), null, new Identifier('string')),
            ],
            'returnType' => new Identifier('bool'),
            'stmts' => [
                new Return_(new ConstFetch(new Name('TRUE'))),
            ],
        ]);
    }
}
