<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3538277
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated DRUPAL_DISABLED, DRUPAL_OPTIONAL, and
// DRUPAL_REQUIRED global constants (and their raw integer equivalents
// 0/1/2) passed to NodeTypeInterface::setPreviewMode() with the
// corresponding NodePreviewMode enum cases introduced in drupal:11.3.0.
// Passing integers or the old constants to setPreviewMode() is
// deprecated and will be removed in drupal:13.0.0.
//
// Before:
//   $nodeType->setPreviewMode(DRUPAL_DISABLED);
//   $nodeType->setPreviewMode(DRUPAL_OPTIONAL);
//   $nodeType->setPreviewMode(DRUPAL_REQUIRED);
//
// After:
//   $nodeType->setPreviewMode(\Drupal\node\NodePreviewMode::Disabled);
//   $nodeType->setPreviewMode(\Drupal\node\NodePreviewMode::Optional);
//   $nodeType->setPreviewMode(\Drupal\node\NodePreviewMode::Required);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\LNumber;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated DRUPAL_DISABLED/OPTIONAL/REQUIRED constants (and raw
 * integers 0/1/2) in NodeTypeInterface::setPreviewMode() calls with the new
 * NodePreviewMode enum cases introduced in drupal:11.3.0.
 */
final class NodeSetPreviewModeRector extends AbstractRector
{
    private const CONST_TO_ENUM = [
        'DRUPAL_DISABLED' => 'Disabled',
        'DRUPAL_OPTIONAL' => 'Optional',
        'DRUPAL_REQUIRED' => 'Required',
    ];

    private const INT_TO_ENUM = [
        0 => 'Disabled',
        1 => 'Optional',
        2 => 'Required',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated DRUPAL_DISABLED/OPTIONAL/REQUIRED constants and integer literals in NodeTypeInterface::setPreviewMode() calls with NodePreviewMode enum cases',
            [
                new CodeSample(
                    '$nodeType->setPreviewMode(DRUPAL_DISABLED);',
                    '$nodeType->setPreviewMode(\\Drupal\\node\\NodePreviewMode::Disabled);'
                ),
                new CodeSample(
                    '$nodeType->setPreviewMode(DRUPAL_OPTIONAL);',
                    '$nodeType->setPreviewMode(\\Drupal\\node\\NodePreviewMode::Optional);'
                ),
                new CodeSample(
                    '$nodeType->setPreviewMode(DRUPAL_REQUIRED);',
                    '$nodeType->setPreviewMode(\\Drupal\\node\\NodePreviewMode::Required);'
                ),
            ]
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
        if (!$this->isName($node->name, 'setPreviewMode')) {
            return null;
        }

        if (count($node->args) !== 1) {
            return null;
        }

        $argValue = $node->args[0]->value;
        $enumCase = null;

        // Handle deprecated global constants: DRUPAL_DISABLED, DRUPAL_OPTIONAL, DRUPAL_REQUIRED.
        if ($argValue instanceof ConstFetch) {
            $constName = $this->getName($argValue);
            if ($constName !== null && isset(self::CONST_TO_ENUM[$constName])) {
                $enumCase = self::CONST_TO_ENUM[$constName];
            }
        }

        // Handle integer literals 0, 1, 2 passed directly.
        if ($enumCase === null && $argValue instanceof LNumber) {
            if (isset(self::INT_TO_ENUM[$argValue->value])) {
                $enumCase = self::INT_TO_ENUM[$argValue->value];
            }
        }

        if ($enumCase === null) {
            return null;
        }

        $node->args[0] = new Arg(
            new ClassConstFetch(
                new FullyQualified('Drupal\node\NodePreviewMode'),
                $enumCase
            )
        );

        return $node;
    }
}
