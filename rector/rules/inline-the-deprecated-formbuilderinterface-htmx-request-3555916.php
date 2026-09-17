<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * As part of the HTMX 4 upgrade, FormBuilderInterface::HTMX_REQUEST is
 * deprecated in favor of HtmxRequestInfoTrait::isHtmxRequest() and will
 * be removed in Drupal 13. Since the trait method needs the consuming
 * class to use the trait and expose getRequest(), which isn't safely
 * inferable in arbitrary contrib code, this rule instead inlines the
 * constant's literal value ('HX-Request') at every use site. This keeps
 * behavior identical while avoiding a fatal error once the constant is
 * removed.
 *
 * Before:
 *   use Drupal\Core\Form\FormBuilderInterface;
 *   
 *   if ($request->headers->has(FormBuilderInterface::HTMX_REQUEST)) {
 *     // ...
 *   }
 *
 * After:
 *   if ($request->headers->has('HX-Request')) {
 *     // ...
 *   }
 *
 * Caveats:
 *   The deprecation message recommends adopting
 *   HtmxRequestInfoTrait::isHtmxRequest() instead, which is the more
 *   idiomatic fix when the calling class already uses that trait. This
 *   rule cannot safely detect that context, so it inlines the
 *   constant's literal string value instead, which is behaviorally
 *   identical and works regardless of the surrounding class.
 *
 * @see https://www.drupal.org/node/3555916
 * @deprecated drupal:12.0.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class InlineFormBuilderHtmxRequestConstantRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace the deprecated FormBuilderInterface::HTMX_REQUEST constant with its literal header name value.',
            [new CodeSample(
                '$request->headers->has(FormBuilderInterface::HTMX_REQUEST);',
                "\$request->headers->has('HX-Request');",
            )],
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
        if (!$node instanceof ClassConstFetch) {
            return null;
        }
        if (!$this->isName($node->name, 'HTMX_REQUEST')) {
            return null;
        }
        if (!$this->isObjectType($node->class, new ObjectType('Drupal\\Core\\Form\\FormBuilderInterface'))) {
            return null;
        }
        return new String_('HX-Request');
    }
}
