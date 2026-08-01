<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal's new Drupal\Core\Routing\Attribute\Route attribute extends
 * Symfony's #[Route] with a top-level title property, replacing the
 * nested defaults: ['_title' => ...] convention. This rule finds
 * #[Route] attributes (from Symfony\Component\Routing\Attribute\Route,
 * including aliased imports) whose defaults array contains a _title key,
 * moves that value to a title: named argument placed after name:, drops
 * _title from defaults (removing the argument entirely if it becomes
 * empty), and repoints the attribute at Drupal's Route subclass.
 *
 * Before:
 *   use Symfony\Component\Routing\Attribute\Route;
 *   
 *   #[Route(
 *     path: '/admin/config/system/site-information',
 *     name: 'system.site_information_settings',
 *     requirements: [
 *       '_permission' => 'administer site configuration',
 *     ],
 *     defaults: ['_title' => new TranslatableMarkup('Basic site settings')],
 *   )]
 *   class SiteInformationForm extends ConfigFormBase {
 *   }
 *
 * After:
 *   use Drupal\Core\Routing\Attribute\Route;
 *   
 *   #[Route(
 *     path: '/admin/config/system/site-information',
 *     name: 'system.site_information_settings',
 *     title: new TranslatableMarkup('Basic site settings'),
 *     requirements: [
 *       '_permission' => 'administer site configuration',
 *     ],
 *   )]
 *   class SiteInformationForm extends ConfigFormBase {
 *   }
 *
 * Caveats:
 *   The new class reference is written as a fully-qualified name
 *   (\Drupal\Core\Routing\Attribute\Route) rather than added as a clean
 *   use import, because safely rewriting imports without colliding with
 *   other, unconverted Route attributes in the same file (e.g. ones
 *   without _title, still bound to the short name Route via the old
 *   import) is out of scope; the emitted code is valid PHP either way.
 *   The rule only handles the _title key; it does not touch
 *   _title_callback/_title_arguments/_title_context defaults, and it
 *   skips attributes where defaults is not a literal array (e.g. a
 *   variable) or where a title: argument is already present.
 *
 * @see https://www.drupal.org/node/3607968
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Attribute;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class PromoteRouteAttributeTitleRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Promote the "_title" route default to the top-level "title" property on #[Route] attributes, switching to Drupal\'s Route attribute subclass.',
            [new CodeSample(
                <<<'CODE_SAMPLE'
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/foo',
    name: 'foo.route',
    defaults: ['_title' => 'Foo'],
)]
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
use Drupal\Core\Routing\Attribute\Route;

#[Route(
    path: '/foo',
    name: 'foo.route',
    title: 'Foo',
)]
CODE_SAMPLE
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /** @param Attribute $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof Attribute) {
            return null;
        }
        if (!$this->isName($node->name, 'Symfony\Component\Routing\Attribute\Route')) {
            return null;
        }

        $defaultsArgIndex = null;
        $titleItemIndex = null;
        $titleValue = null;
        $hasTitleArg = false;
        foreach ($node->args as $i => $arg) {
            if ($arg->name === null) {
                continue;
            }
            if ($arg->name->toString() === 'title') {
                $hasTitleArg = true;
                continue;
            }
            if ($arg->name->toString() !== 'defaults') {
                continue;
            }
            if (!$arg->value instanceof Array_) {
                return null;
            }
            foreach ($arg->value->items as $j => $item) {
                if ($item === null || $item->key === null) {
                    continue;
                }
                if (!$item->key instanceof String_ || $item->key->value !== '_title') {
                    continue;
                }
                $defaultsArgIndex = $i;
                $titleItemIndex = $j;
                $titleValue = $item->value;
            }
        }

        if ($hasTitleArg || $defaultsArgIndex === null || $titleItemIndex === null) {
            return null;
        }

        // Remove the `_title` entry from defaults.
        $defaultsArg = $node->args[$defaultsArgIndex];
        assert($defaultsArg->value instanceof Array_);
        unset($defaultsArg->value->items[$titleItemIndex]);
        $defaultsArg->value->items = array_values($defaultsArg->value->items);

        // Drop the whole `defaults` argument if it is now empty.
        if ($defaultsArg->value->items === []) {
            unset($node->args[$defaultsArgIndex]);
        }
        $node->args = array_values($node->args);

        // Insert a `title:` named argument, right after `name:` when present, otherwise first.
        $titleArg = new Arg($titleValue, false, false, [], new Identifier('title'));
        $insertAt = 0;
        foreach ($node->args as $i => $arg) {
            if ($arg->name !== null && $arg->name->toString() === 'name') {
                $insertAt = $i + 1;
                break;
            }
        }
        array_splice($node->args, $insertAt, 0, [$titleArg]);

        // Point the attribute at Drupal's Route subclass instead of Symfony's.
        $node->name = new FullyQualified('Drupal\Core\Routing\Attribute\Route');

        return $node;
    }
}
