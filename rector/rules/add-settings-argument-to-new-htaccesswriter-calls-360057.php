<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/360057
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.2.0 deprecated instantiating HtaccessWriter without passing
// a Settings object as the third constructor argument; drupal:12.0.0
// will require it. This rule detects new HtaccessWriter($logger,
// $streamWrapperManager) calls with only two arguments and appends
// \Drupal::service('settings') as the third argument, eliminating the
// E_USER_DEPRECATED trigger_error.
//
// Before:
//   new \Drupal\Core\File\HtaccessWriter($logger, $streamWrapperManager);
//
// After:
//   new \Drupal\Core\File\HtaccessWriter($logger, $streamWrapperManager, \Drupal::service('settings'));


use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the required $settings argument to new HtaccessWriter() calls.
 *
 * Drupal 11.2.0 deprecated calling HtaccessWriter::__construct() without
 * the third $settings argument. Drupal 12.0.0 will require it.
 */
final class HtaccessWriterSettingsArgumentRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add required $settings argument to new HtaccessWriter() calls deprecated in drupal:11.2.0',
            [
                new CodeSample(
                    'new \Drupal\Core\File\HtaccessWriter($logger, $streamWrapperManager);',
                    'new \Drupal\Core\File\HtaccessWriter($logger, $streamWrapperManager, \Drupal::service(\'settings\'));'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    /** @param New_ $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->class instanceof FullyQualified) {
            return null;
        }

        if (!$this->isName($node->class, 'Drupal\Core\File\HtaccessWriter')) {
            return null;
        }

        // Only modify calls with exactly 2 arguments (the deprecated pattern).
        if (count($node->args) !== 2) {
            return null;
        }

        // Add \Drupal::service('settings') as the third argument.
        $settingsArg = $this->nodeFactory->createArg(
            $this->nodeFactory->createStaticCall('Drupal', 'service', [
                $this->nodeFactory->createArg('settings'),
            ])
        );

        $node->args[] = $settingsArg;

        return $node;
    }
}
