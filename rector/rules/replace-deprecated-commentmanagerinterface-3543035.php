<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3543035
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.3 deprecates CommentManagerInterface::getCountNewComments(),
// which is removed in 12.0. The implementation moved to
// \Drupal\history\HistoryManager::getCountNewComments(). This rule
// rewrites any call on an object typed as CommentManagerInterface to use
// \Drupal::service(\Drupal\history\HistoryManager::class)-
// >getCountNewComments(), preserving all arguments.
//
// Before:
//   $this->commentManager->getCountNewComments($entity, $fieldName, 0)
//
// After:
//   \Drupal::service(\Drupal\history\HistoryManager::class)->getCountNewComments($entity, $fieldName, 0)


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated CommentManagerInterface::getCountNewComments() calls
 * with the new HistoryManager::getCountNewComments() equivalent.
 */
final class CommentManagerGetCountNewCommentsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated CommentManagerInterface::getCountNewComments() with \Drupal::service(\Drupal\history\HistoryManager::class)->getCountNewComments()',
            [
                new CodeSample(
                    '$this->commentManager->getCountNewComments($entity)',
                    '\Drupal::service(\Drupal\history\HistoryManager::class)->getCountNewComments($entity)',
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
        if (!$this->isName($node->name, 'getCountNewComments')) {
            return null;
        }

        if (!$this->isObjectType($node->var, new ObjectType('Drupal\comment\CommentManagerInterface'))) {
            return null;
        }

        // Build: \Drupal::service(\Drupal\history\HistoryManager::class)
        $drupalService = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new ClassConstFetch(new FullyQualified('Drupal\history\HistoryManager'), 'class'))]
        );

        return new MethodCall($drupalService, 'getCountNewComments', $node->args);
    }
}
