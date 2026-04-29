<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/2907780
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces the deprecated procedural field_purge_batch() function with
// the equivalent call on the \Drupal\Core\Field\FieldPurger service
// introduced in Drupal 11.4. The function was deprecated in
// drupal:11.4.0 and will be removed in drupal:13.0.0. Both the single-
// argument and two-argument forms (with optional
// $field_storage_unique_id) are handled.
//
// Before:
//   field_purge_batch(10);
//
// After:
//   \Drupal::service(\Drupal\Core\Field\FieldPurger::class)->purgeBatch(10);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FieldPurgeBatchRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated field_purge_batch() with \Drupal\Core\Field\FieldPurger service purgeBatch() call',
            [
                new CodeSample(
                    'field_purge_batch(10);',
                    '\Drupal::service(\Drupal\Core\Field\FieldPurger::class)->purgeBatch(10);'
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
        if (!$this->isName($node, 'field_purge_batch')) {
            return null;
        }

        $classConstFetch = $this->nodeFactory->createClassConstFetch(
            'Drupal\Core\Field\FieldPurger',
            'class'
        );
        $serviceCall = $this->nodeFactory->createStaticCall('Drupal', 'service', [$classConstFetch]);
        return $this->nodeFactory->createMethodCall($serviceCall, 'purgeBatch', $node->args);
    }
}
