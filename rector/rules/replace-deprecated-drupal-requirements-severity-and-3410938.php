<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated drupal_requirements_severity() function and
 * SystemManager::getMaxSeverity() method with the new
 * RequirementSeverity::maxSeverityFromRequirements() static method
 * introduced in Drupal 11.2. Both are deprecated in drupal:11.2.0 and
 * removed in drupal:12.0.0. The new method returns a RequirementSeverity
 * enum instance instead of an integer.
 *
 * Before:
 *   $severity = drupal_requirements_severity($requirements);
 *   $max = $systemManager->getMaxSeverity($requirements);
 *
 * After:
 *   $severity = \Drupal\Core\Extension\Requirement\RequirementSeverity::maxSeverityFromRequirements($requirements);
 *   $max = \Drupal\Core\Extension\Requirement\RequirementSeverity::maxSeverityFromRequirements($requirements);
 *
 * Caveats:
 *   The return type changes from int to a RequirementSeverity enum
 *   instance. Callers that compare the result to integer REQUIREMENT_*
 *   constants need those constants replaced as well (covered by a
 *   separate rule for REQUIREMENT_ERROR, REQUIREMENT_WARNING, etc.).
 *   StatusReport::getSeverities() is also deprecated with no
 *   replacement and cannot be automated.
 *
 * @see https://www.drupal.org/node/3410938
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use Rector\Config\RectorConfig;
use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;
use Rector\Transform\ValueObject\MethodCallToStaticCall;

return RectorConfig::configure()
    ->withConfiguredRule(FuncCallToStaticCallRector::class, [
        new FuncCallToStaticCall(
            'drupal_requirements_severity',
            'Drupal\Core\Extension\Requirement\RequirementSeverity',
            'maxSeverityFromRequirements'
        ),
    ])
    ->withConfiguredRule(MethodCallToStaticCallRector::class, [
        new MethodCallToStaticCall(
            'Drupal\system\SystemManager',
            'getMaxSeverity',
            'Drupal\Core\Extension\Requirement\RequirementSeverity',
            'maxSeverityFromRequirements'
        ),
    ]);
