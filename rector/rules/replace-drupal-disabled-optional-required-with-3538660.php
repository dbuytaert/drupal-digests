<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.3 introduces CommentPreviewMode enum to replace the global
 * constants DRUPAL_DISABLED, DRUPAL_OPTIONAL, and DRUPAL_REQUIRED in
 * comment preview contexts. This rule rewrites setCommentPreview() call
 * arguments so contrib and custom test classes extending CommentTestBase
 * use the new CommentPreviewMode::Disabled,
 * CommentPreviewMode::Optional, and CommentPreviewMode::Required enum
 * cases instead of the deprecated integer constants.
 *
 * Before:
 *   $this->setCommentPreview(DRUPAL_DISABLED);
 *   $this->setCommentPreview(DRUPAL_OPTIONAL);
 *   $this->setCommentPreview(DRUPAL_REQUIRED);
 *
 * After:
 *   $this->setCommentPreview(\Drupal\comment\CommentPreviewMode::Disabled);
 *   $this->setCommentPreview(\Drupal\comment\CommentPreviewMode::Optional);
 *   $this->setCommentPreview(\Drupal\comment\CommentPreviewMode::Required);
 *
 * @see https://www.drupal.org/node/3538660
 * @deprecated drupal:11.3.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CommentPreviewModeRector extends AbstractRector
{
    private const CONSTANT_TO_ENUM_CASE = [
        'DRUPAL_DISABLED' => 'Disabled',
        'DRUPAL_OPTIONAL' => 'Optional',
        'DRUPAL_REQUIRED' => 'Required',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace DRUPAL_DISABLED/OPTIONAL/REQUIRED with CommentPreviewMode enum in setCommentPreview() calls',
            [
                new CodeSample(
                    '$this->setCommentPreview(DRUPAL_DISABLED);',
                    '$this->setCommentPreview(\Drupal\comment\CommentPreviewMode::Disabled);'
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
        if (!$this->isName($node->name, 'setCommentPreview')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Drupal\node\NodeTypeInterface'))) {
            return null;
        }

        if (!isset($node->args[0]) || !$node->args[0] instanceof Arg) {
            return null;
        }

        $argValue = $node->args[0]->value;
        if (!$argValue instanceof ConstFetch) {
            return null;
        }

        $constName = $this->getName($argValue);
        if ($constName === null || !isset(self::CONSTANT_TO_ENUM_CASE[$constName])) {
            return null;
        }

        $enumCase = self::CONSTANT_TO_ENUM_CASE[$constName];
        $node->args[0] = new Arg(
            $this->nodeFactory->createClassConstFetch('Drupal\comment\CommentPreviewMode', $enumCase)
        );

        return $node;
    }
}
