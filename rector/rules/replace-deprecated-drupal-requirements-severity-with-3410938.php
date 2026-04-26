<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3410938
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2 deprecated the drupal_requirements_severity() procedural
// function in favour of the new \Drupal\Core\Extension\Requirement\Requi
// rementSeverity::maxSeverityFromRequirements() static method. The new
// method returns a typed RequirementSeverity enum instead of a raw int,
// improving type safety throughout the requirements system. This rule
// automates the call-site migration.
//
// Before:
//   $severity = drupal_requirements_severity($requirements);
//
// After:
//   $severity = \Drupal\Core\Extension\Requirement\RequirementSeverity::maxSeverityFromRequirements($requirements);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated drupal_requirements_severity() with
 * RequirementSeverity::maxSeverityFromRequirements().
 */
final class DrupalRequirementsSeverityToEnumRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated drupal_requirements_severity() with \\Drupal\\Core\\Extension\\Requirement\\RequirementSeverity::maxSeverityFromRequirements()',
            [
                new CodeSample(
                    '$severity = drupal_requirements_severity($requirements);',
                    '$severity = \\Drupal\\Core\\Extension\\Requirement\\RequirementSeverity::maxSeverityFromRequirements($requirements);'
                ),
            ]
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
        if (!$this->isName($node, 'drupal_requirements_severity')) {
            return null;
        }

        return $this->nodeFactory->createStaticCall(
            'Drupal\\Core\\Extension\\Requirement\\RequirementSeverity',
            'maxSeverityFromRequirements',
            $node->args
        );
    }
}
