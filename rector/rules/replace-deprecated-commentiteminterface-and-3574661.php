<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3574661
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the three CommentItemInterface::HIDDEN/CLOSED/OPEN integer
// constants to their CommentingStatus::Hidden/Closed/Open enum
// equivalents, and the three CommentInterface::ANONYMOUS_* constants to
// AnonymousContact::Forbidden/Allowed/Required. Both sets were
// deprecated in drupal:11.4.0 and removed in drupal:13.0.0 (issue
// #3574661). Passing the old integer constants after removal causes a
// type mismatch at runtime.
//
// Before:
//   use Drupal\comment\Plugin\Field\FieldType\CommentItemInterface;
//   use Drupal\comment\CommentInterface;
//   
//   $status = CommentItemInterface::HIDDEN;
//   $anon   = CommentInterface::ANONYMOUS_MAY_CONTACT;
//
// After:
//   $status = \Drupal\comment\CommentingStatus::Hidden;
//   $anon   = \Drupal\comment\AnonymousContact::Allowed;


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated CommentItemInterface and CommentInterface integer
 * constants with their CommentingStatus and AnonymousContact enum equivalents.
 *
 * CommentItemInterface::HIDDEN/CLOSED/OPEN were deprecated in drupal:11.4.0
 * and removed in drupal:13.0.0; the replacements are CommentingStatus::Hidden,
 * ::Closed, and ::Open respectively.
 *
 * CommentInterface::ANONYMOUS_MAYNOT_CONTACT/ANONYMOUS_MAY_CONTACT/
 * ANONYMOUS_MUST_CONTACT were deprecated in drupal:11.4.0 and removed in
 * drupal:13.0.0; the replacements are AnonymousContact::Forbidden, ::Allowed,
 * and ::Required respectively.
 */
final class ReplaceCommentDeprecatedConstantsRector extends AbstractRector
{
    private const COMMENT_ITEM_INTERFACE = 'Drupal\\comment\\Plugin\\Field\\FieldType\\CommentItemInterface';
    private const COMMENTING_STATUS      = 'Drupal\\comment\\CommentingStatus';

    private const COMMENT_INTERFACE      = 'Drupal\\comment\\CommentInterface';
    private const ANONYMOUS_CONTACT      = 'Drupal\\comment\\AnonymousContact';

    private const ITEM_MAP = [
        'HIDDEN' => 'Hidden',
        'CLOSED' => 'Closed',
        'OPEN'   => 'Open',
    ];

    private const ANON_MAP = [
        'ANONYMOUS_MAYNOT_CONTACT' => 'Forbidden',
        'ANONYMOUS_MAY_CONTACT'    => 'Allowed',
        'ANONYMOUS_MUST_CONTACT'   => 'Required',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated CommentItemInterface and CommentInterface constants with CommentingStatus and AnonymousContact enum cases',
            [
                new CodeSample(
                    'CommentItemInterface::HIDDEN;',
                    '\\Drupal\\comment\\CommentingStatus::Hidden;'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [ClassConstFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof ClassConstFetch) {
            return null;
        }

        $constName = $node->name instanceof Node\Identifier
            ? $node->name->toString()
            : null;

        if ($constName === null) {
            return null;
        }

        if ($this->isName($node->class, self::COMMENT_ITEM_INTERFACE)
            && isset(self::ITEM_MAP[$constName])
        ) {
            return new ClassConstFetch(
                new FullyQualified(self::COMMENTING_STATUS),
                self::ITEM_MAP[$constName]
            );
        }

        if ($this->isName($node->class, self::COMMENT_INTERFACE)
            && isset(self::ANON_MAP[$constName])
        ) {
            return new ClassConstFetch(
                new FullyQualified(self::ANONYMOUS_CONTACT),
                self::ANON_MAP[$constName]
            );
        }

        return null;
    }
}
