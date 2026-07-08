<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Calling ImageFormatter, ImageUrlFormatter, or MediaThumbnailFormatter
 * constructors without the $imageDerivativeUtilities argument is
 * deprecated in drupal:11.4.0 and will be required in drupal:12.0.0.
 * This rule detects direct new ClassName(...) calls that use the pre-
 * deprecation argument count and appends
 * \Drupal::service(\Drupal\image\ImageDerivativeUtilities::class) as the
 * final argument.
 *
 * Before:
 *   new \Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter(
 *       $pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty,
 *       $currentUser, $imageStyleStorage, $fileUrlGenerator
 *   );
 *
 * After:
 *   new \Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter(
 *       $pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty,
 *       $currentUser, $imageStyleStorage, $fileUrlGenerator,
 *       \Drupal::service(\Drupal\image\ImageDerivativeUtilities::class)
 *   );
 *
 * Caveats:
 *   Handles direct new ClassName(...) instantiation only. Contrib
 *   subclasses that call parent::__construct() without the new
 *   argument, and any new static(...) patterns in factory methods,
 *   require manual updates: accept ?ImageDerivativeUtilities
 *   $imageDerivativeUtilities = NULL as a new constructor parameter,
 *   pass it to parent::__construct(), and inject
 *   \Drupal::service(ImageDerivativeUtilities::class) from the create()
 *   factory.
 *
 * @see https://www.drupal.org/node/3609124
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddImageDerivativeUtilitiesToImageFormatterRector extends AbstractRector
{
    // Maps each affected class FQCN to the argument count BEFORE the new
    // $imageDerivativeUtilities parameter was added in drupal:11.4.0.
    private const CLASS_OLD_ARG_COUNT = [
        'Drupal\\image\\Plugin\\Field\\FieldFormatter\\ImageFormatter' => 10,
        'Drupal\\image\\Plugin\\Field\\FieldFormatter\\ImageUrlFormatter' => 9,
        'Drupal\\media\\Plugin\\Field\\FieldFormatter\\MediaThumbnailFormatter' => 11,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the missing $imageDerivativeUtilities service argument to ImageFormatter, ImageUrlFormatter, and MediaThumbnailFormatter constructor calls deprecated in drupal:11.4.0.',
            [new CodeSample(
                'new \\Drupal\\image\\Plugin\\Field\\FieldFormatter\\ImageFormatter($pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty, $currentUser, $imageStyleStorage, $fileUrlGenerator);',
                'new \\Drupal\\image\\Plugin\\Field\\FieldFormatter\\ImageFormatter($pluginId, $pluginDef, $fieldDef, $settings, $label, $viewMode, $thirdParty, $currentUser, $imageStyleStorage, $fileUrlGenerator, \\Drupal::service(\\Drupal\\image\\ImageDerivativeUtilities::class));',
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
        foreach (self::CLASS_OLD_ARG_COUNT as $fqcn => $oldArgCount) {
            if (!$this->isName($node->class, $fqcn)) {
                continue;
            }
            if (count($node->args) !== $oldArgCount) {
                return null;
            }
            $serviceCall = $this->nodeFactory->createStaticCall(
                'Drupal',
                'service',
                [$this->nodeFactory->createClassConstReference('Drupal\\image\\ImageDerivativeUtilities')]
            );
            $node->args[] = $this->nodeFactory->createArg($serviceCall);
            return $node;
        }
        return null;
    }
}
