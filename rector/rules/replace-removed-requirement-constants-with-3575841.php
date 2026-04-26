<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3575841
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the four global constants REQUIREMENT_INFO, REQUIREMENT_OK,
// REQUIREMENT_WARNING, REQUIREMENT_ERROR (defined in install.inc) and
// the three SystemManager::REQUIREMENT_* class constants to their
// \Drupal\Core\Extension\Requirement\RequirementSeverity enum
// equivalents. All were deprecated in drupal:11.2.0 and removed in
// drupal:12.0.0 (issue #3575841). Module authors use these constants
// heavily in hook_requirements() implementations.
//
// Before:
//   $requirements['mymodule']['severity'] = REQUIREMENT_ERROR;
//   $ok = SystemManager::REQUIREMENT_OK;
//
// After:
//   $requirements['mymodule']['severity'] = \Drupal\Core\Extension\Requirement\RequirementSeverity::Error;
//   $ok = \Drupal\Core\Extension\Requirement\RequirementSeverity::OK;


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the removed REQUIREMENT_* global constants and SystemManager::REQUIREMENT_*
 * class constants with RequirementSeverity enum cases.
 *
 * The four global constants (REQUIREMENT_INFO, REQUIREMENT_OK,
 * REQUIREMENT_WARNING, REQUIREMENT_ERROR) defined in install.inc, and the
 * three SystemManager class constants, were deprecated in drupal:11.2.0 and
 * removed in drupal:12.0.0 (issue #3575841). The replacement is the
 * \Drupal\Core\Extension\Requirement\RequirementSeverity backed enum.
 */
final class ReplaceRequirementConstantsRector extends AbstractRector
{
    private const REQUIREMENT_SEVERITY = 'Drupal\\Core\\Extension\\Requirement\\RequirementSeverity';
    private const SYSTEM_MANAGER       = 'Drupal\\system\\SystemManager';

    /** Global const name => RequirementSeverity case name */
    private const GLOBAL_CONST_MAP = [
        'REQUIREMENT_INFO'    => 'Info',
        'REQUIREMENT_OK'      => 'OK',
        'REQUIREMENT_WARNING' => 'Warning',
        'REQUIREMENT_ERROR'   => 'Error',
    ];

    /** SystemManager class const name => RequirementSeverity case name */
    private const CLASS_CONST_MAP = [
        'REQUIREMENT_OK'      => 'OK',
        'REQUIREMENT_WARNING' => 'Warning',
        'REQUIREMENT_ERROR'   => 'Error',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed REQUIREMENT_* global constants and SystemManager::REQUIREMENT_* with RequirementSeverity enum cases',
            [
                new CodeSample(
                    "\$requirements['check']['severity'] = REQUIREMENT_ERROR;",
                    "\$requirements['check']['severity'] = \\Drupal\\Core\\Extension\\Requirement\\RequirementSeverity::Error;"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ConstFetch::class, ClassConstFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ConstFetch) {
            return $this->refactorGlobalConst($node);
        }

        if ($node instanceof ClassConstFetch) {
            return $this->refactorClassConst($node);
        }

        return null;
    }

    private function refactorGlobalConst(ConstFetch $node): ?ClassConstFetch
    {
        // Normalise: strip leading backslash for lookup.
        $name = $node->name->toString();
        $shortName = ltrim($name, '\\');

        if (!isset(self::GLOBAL_CONST_MAP[$shortName])) {
            return null;
        }

        return new ClassConstFetch(
            new FullyQualified(self::REQUIREMENT_SEVERITY),
            self::GLOBAL_CONST_MAP[$shortName]
        );
    }

    private function refactorClassConst(ClassConstFetch $node): ?ClassConstFetch
    {
        if (!$node->name instanceof Node\Identifier) {
            return null;
        }

        $constName = $node->name->toString();

        if (!isset(self::CLASS_CONST_MAP[$constName])) {
            return null;
        }

        // Match SystemManager by short name or FQCN.
        if (!$this->isName($node->class, self::SYSTEM_MANAGER)) {
            return null;
        }

        return new ClassConstFetch(
            new FullyQualified(self::REQUIREMENT_SEVERITY),
            self::CLASS_CONST_MAP[$constName]
        );
    }
}
