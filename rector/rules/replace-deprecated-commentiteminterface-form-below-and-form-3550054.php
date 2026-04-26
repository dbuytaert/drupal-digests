<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3550054
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated CommentItemInterface::FORM_BELOW and
// CommentItemInterface::FORM_SEPARATE_PAGE integer constants with the
// \Drupal\comment\FormLocation backed enum cases FormLocation::Below and
// FormLocation::SeparatePage. Both constants were deprecated in Drupal
// 11.4.0 and will be removed in 13.0.0. The new enum is type-safe and
// integrates cleanly with PHP match expressions and strict comparisons.
//
// Before:
//   use Drupal\comment\Plugin\Field\FieldType\CommentItemInterface;
//   
//   $location = CommentItemInterface::FORM_BELOW;
//   $other    = CommentItemInterface::FORM_SEPARATE_PAGE;
//
// After:
//   $location = \Drupal\comment\FormLocation::Below;
//   $other    = \Drupal\comment\FormLocation::SeparatePage;


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FormLocationRector extends AbstractRector
{
    /**
     * Maps deprecated CommentItemInterface constants to FormLocation enum cases.
     */
    private const MAP = [
        'FORM_BELOW' => 'Below',
        'FORM_SEPARATE_PAGE' => 'SeparatePage',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated CommentItemInterface::FORM_BELOW and FORM_SEPARATE_PAGE constants with FormLocation enum cases.',
            [
                new CodeSample(
                    'use Drupal\\comment\\Plugin\\Field\\FieldType\\CommentItemInterface;

$location = CommentItemInterface::FORM_BELOW;
$other = CommentItemInterface::FORM_SEPARATE_PAGE;',
                    '$location = \\Drupal\\comment\\FormLocation::Below;
$other = \\Drupal\\comment\\FormLocation::SeparatePage;'
                ),
            ]
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
        if (!$node->name instanceof Identifier) {
            return null;
        }

        $constName = $node->name->toString();
        if (!array_key_exists($constName, self::MAP)) {
            return null;
        }

        if (!$this->isName($node->class, 'Drupal\\comment\\Plugin\\Field\\FieldType\\CommentItemInterface')) {
            return null;
        }

        $enumCase = self::MAP[$constName];

        return new ClassConstFetch(
            new FullyQualified('Drupal\\comment\\FormLocation'),
            new Identifier($enumCase)
        );
    }
}
