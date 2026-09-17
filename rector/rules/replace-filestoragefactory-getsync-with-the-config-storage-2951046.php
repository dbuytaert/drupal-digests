<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal\Core\Config\FileStorageFactory::getSync() is deprecated in
 * favor of the config.storage.sync service, which now wraps the sync
 * directory in an AutoloadingStorage decorator so PHP constants and
 * enums referenced in YAML config can be resolved even for not-yet-
 * installed extensions. This rule rewrites zero-argument calls to the
 * static factory method into a call to
 * \Drupal::service('config.storage.sync'), which contrib code commonly
 * uses to read or compare staged configuration.
 *
 * Before:
 *   $sync = \Drupal\Core\Config\FileStorageFactory::getSync();
 *
 * After:
 *   $sync = \Drupal::service('config.storage.sync');
 *
 * Caveats:
 *   Only rewrites zero-argument calls to getSync(); the method takes no
 *   parameters upstream, so this is not a real limitation. Does not
 *   rewrite new FileStorageFactory() construction, which is also
 *   deprecated but rare in practice since the class exposes no other
 *   usable API once instantiated.
 *
 * @see https://www.drupal.org/node/2951046
 * @deprecated drupal:11.5.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReplaceFileStorageFactoryGetSyncRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace FileStorageFactory::getSync() with the config.storage.sync service.',
            [new CodeSample(
                '$sync = \Drupal\Core\Config\FileStorageFactory::getSync();',
                "\$sync = \\Drupal::service('config.storage.sync');",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /** @param StaticCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof StaticCall) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\\Core\\Config\\FileStorageFactory')) {
            return null;
        }
        if (!$this->isName($node->name, 'getSync')) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }
        return new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('config.storage.sync'))],
        );
    }
}
