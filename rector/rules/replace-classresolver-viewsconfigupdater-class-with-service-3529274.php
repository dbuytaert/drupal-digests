<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3529274
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces \Drupal::classResolver(ViewsConfigUpdater::class) with
// \Drupal::service(ViewsConfigUpdater::class) following Drupal issue
// #3529274. classResolver creates a new instance on every call, so state
// set via setDeprecationsEnabled(FALSE) does not persist across hooks.
// ViewsConfigUpdater is now a registered service, making it a singleton
// that retains state across the request lifecycle.
//
// Before:
//   $view_config_updater = \Drupal::classResolver(ViewsConfigUpdater::class);
//
// After:
//   $view_config_updater = \Drupal::service(ViewsConfigUpdater::class);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces \Drupal::classResolver(ViewsConfigUpdater::class) with
 * \Drupal::service(ViewsConfigUpdater::class) now that ViewsConfigUpdater
 * is a proper service.
 */
final class ViewsConfigUpdaterClassResolverToServiceRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace \\Drupal::classResolver(ViewsConfigUpdater::class) with \\Drupal::service(ViewsConfigUpdater::class) since ViewsConfigUpdater is now registered as a service.',
            [
                new CodeSample(
                    '\\Drupal::classResolver(ViewsConfigUpdater::class)',
                    '\\Drupal::service(ViewsConfigUpdater::class)'
                ),
            ]
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
        // Must be a call on \Drupal (or Drupal).
        if (!$this->isName($node->class, 'Drupal')) {
            return null;
        }

        // Must be calling ::classResolver().
        if (!$this->isName($node->name, 'classResolver')) {
            return null;
        }

        // Must have exactly one argument.
        if (count($node->args) !== 1) {
            return null;
        }

        $arg = $node->args[0];
        if (!$arg instanceof Arg) {
            return null;
        }

        $value = $arg->value;

        // The argument must be ViewsConfigUpdater::class.
        if (!$value instanceof ClassConstFetch) {
            return null;
        }

        if (!$this->isName($value->name, 'class')) {
            return null;
        }

        if (!$this->isName($value->class, 'Drupal\\views\\ViewsConfigUpdater')) {
            return null;
        }

        // Replace classResolver with service.
        $node->name = new Identifier('service');
        return $node;
    }
}
