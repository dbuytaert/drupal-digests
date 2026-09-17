<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Rewrites new NodePermissions() (zero-argument form) to new
 * NodePermissions(\Drupal::entityTypeManager()). Calling
 * NodePermissions::__construct() without the $entityTypeManager argument
 * was deprecated in drupal:11.2.0 and will be required in drupal:12.0.0.
 * Contrib and custom code that instantiates NodePermissions directly
 * (for example in tests or service decorators) needs this argument
 * added.
 *
 * Before:
 *   new \Drupal\node\NodePermissions()
 *
 * After:
 *   new \Drupal\node\NodePermissions(\Drupal::entityTypeManager())
 *
 * Caveats:
 *   Only rewrites zero-argument new NodePermissions() calls. Code that
 *   already passes the entity type manager is unchanged. Code using
 *   NodePermissions as a service (instantiated by the container via
 *   ContainerInjectionInterface) does not need this rewrite.
 *
 * @see https://www.drupal.org/node/3092001
 * @deprecated drupal:11.2.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class NodePermissionsConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Pass EntityTypeManagerInterface to NodePermissions constructor, required since drupal:11.2.0.',
            [new CodeSample(
                'new \\Drupal\\node\\NodePermissions()',
                'new \\Drupal\\node\\NodePermissions(\\Drupal::entityTypeManager())',
            )],
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
        if (!$node instanceof New_) {
            return null;
        }
        if (!$this->isName($node->class, 'Drupal\\node\\NodePermissions')) {
            return null;
        }
        if (count($node->args) !== 0) {
            return null;
        }
        $node->args = [
            new Arg(
                new StaticCall(
                    new FullyQualified('Drupal'),
                    'entityTypeManager',
                )
            ),
        ];
        return $node;
    }
}
