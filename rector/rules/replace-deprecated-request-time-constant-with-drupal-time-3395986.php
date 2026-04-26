<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3395986
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces uses of the REQUEST_TIME constant, deprecated in drupal:8.3.0
// and removed in drupal:11.0.0, with the canonical
// \Drupal::time()->getRequestTime() call. The constant was defined in
// core/includes/bootstrap.inc as (int) $_SERVER['REQUEST_TIME']; using
// the time service instead keeps code compatible with Drupal 11 and
// avoids reliance on a procedural global.
//
// Before:
//   $cutoff = REQUEST_TIME - $lifespan;
//
// After:
//   $cutoff = \Drupal::time()->getRequestTime() - $lifespan;


use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces the deprecated REQUEST_TIME constant with \Drupal::time()->getRequestTime().
 */
final class ReplaceRequestTimeConstantRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated REQUEST_TIME constant with \\Drupal::time()->getRequestTime()',
            [
                new CodeSample(
                    '$cutoff = REQUEST_TIME - $lifespan;',
                    '$cutoff = \\Drupal::time()->getRequestTime() - $lifespan;',
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ConstFetch::class];
    }

    /** @param ConstFetch $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node, 'REQUEST_TIME')) {
            return null;
        }

        // Build \Drupal::time()
        $staticCall = $this->nodeFactory->createStaticCall('Drupal', 'time');

        // Build \Drupal::time()->getRequestTime()
        return $this->nodeFactory->createMethodCall($staticCall, 'getRequestTime');
    }
}
