<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Finds new ResponsiveImageFormatter(...) calls that pass only 11
 * arguments and appends \Drupal::service('file_url_generator') as the
 * 12th argument. In drupal:11.4.0 the $fileUrlGenerator parameter was
 * added to the constructor and calling without it triggers a
 * deprecation. Contrib modules that manually instantiate this formatter
 * need this argument before drupal:12.0.0 removes backward
 * compatibility.
 *
 * Before:
 *   new \Drupal\responsive_image\Plugin\Field\FieldFormatter\ResponsiveImageFormatter(
 *       $pluginId, $pluginDef, $fieldDef, $settings,
 *       $label, $viewMode, $thirdParty,
 *       $responsiveImageStyleStorage, $imageStyleStorage,
 *       $linkGenerator, $currentUser
 *   );
 *
 * After:
 *   new \Drupal\responsive_image\Plugin\Field\FieldFormatter\ResponsiveImageFormatter(
 *       $pluginId, $pluginDef, $fieldDef, $settings,
 *       $label, $viewMode, $thirdParty,
 *       $responsiveImageStyleStorage, $imageStyleStorage,
 *       $linkGenerator, $currentUser,
 *       \Drupal::service('file_url_generator')
 *   );
 *
 * Caveats:
 *   Only rewrites calls with exactly 11 positional arguments. Calls
 *   using named arguments or a different argument count are not
 *   touched.
 *
 * @see https://www.drupal.org/node/3064751
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddFileUrlGeneratorToResponsiveImageFormatterRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the $fileUrlGenerator argument to new ResponsiveImageFormatter() calls that omit it.',
            [new CodeSample(
                'new \Drupal\responsive_image\Plugin\Field\FieldFormatter\ResponsiveImageFormatter($pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty, $responsiveImageStyleStorage, $imageStyleStorage, $linkGenerator, $currentUser);',
                'new \Drupal\responsive_image\Plugin\Field\FieldFormatter\ResponsiveImageFormatter($pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty, $responsiveImageStyleStorage, $imageStyleStorage, $linkGenerator, $currentUser, \Drupal::service(\'file_url_generator\'));',
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
        if (!$this->isName($node->class, 'Drupal\\responsive_image\\Plugin\\Field\\FieldFormatter\\ResponsiveImageFormatter')) {
            return null;
        }
        // Only rewrite when the $fileUrlGenerator argument (12th) is missing.
        if (count($node->args) !== 11) {
            return null;
        }
        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg(new String_('file_url_generator'))],
        );
        $node->args[] = new Arg($serviceCall);
        return $node;
    }
}
