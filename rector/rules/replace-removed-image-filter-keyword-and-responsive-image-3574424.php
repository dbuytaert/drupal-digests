<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3574424
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites five procedural functions removed in drupal:12.0.0 (issue
// #3574424): image_filter_keyword() becomes
// \Drupal\Component\Utility\Image::getKeywordOffset(), and the four
// _responsive_image_build_source_attributes(),
// responsive_image_get_image_dimensions(),
// responsive_image_get_mime_type(), and
// _responsive_image_image_style_url() become method calls on
// \Drupal::service(ResponsiveImageBuilder::class). All arguments are
// preserved.
//
// Before:
//   // Deprecated in drupal:11.1.0 / drupal:11.3.0, removed in drupal:12.0.0
//   $offset = image_filter_keyword($anchor, $current_size, $new_size);
//   $mime   = responsive_image_get_mime_type($image_style_name, $extension);
//   $url    = _responsive_image_image_style_url($style_name, $path);
//
// After:
//   $offset = \Drupal\Component\Utility\Image::getKeywordOffset($anchor, $current_size, $new_size);
//   $mime   = \Drupal::service(\Drupal\responsive_image\ResponsiveImageBuilder::class)->getMimeType($image_style_name, $extension);
//   $url    = \Drupal::service(\Drupal\responsive_image\ResponsiveImageBuilder::class)->getImageStyleUrl($style_name, $path);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces removed procedural functions from image and responsive_image modules.
 *
 * Several procedural functions were deprecated in drupal:11.1.0 / drupal:11.3.0
 * and removed in drupal:12.0.0 (issue #3574424):
 *
 * - image_filter_keyword($anchor, $current_size, $new_size)
 *   => \Drupal\Component\Utility\Image::getKeywordOffset($anchor, $current, $new)
 *
 * - _responsive_image_build_source_attributes($variables, $breakpoint, $multipliers)
 *   => \Drupal::service(ResponsiveImageBuilder::class)->buildSourceAttributes(...)
 *
 * - responsive_image_get_image_dimensions($style_name, $dimensions, $uri)
 *   => \Drupal::service(ResponsiveImageBuilder::class)->getImageDimensions(...)
 *
 * - responsive_image_get_mime_type($style_name, $extension)
 *   => \Drupal::service(ResponsiveImageBuilder::class)->getMimeType(...)
 *
 * - _responsive_image_image_style_url($style_name, $path)
 *   => \Drupal::service(ResponsiveImageBuilder::class)->getImageStyleUrl(...)
 */
final class ReplaceRemovedImageResponsiveImageFunctionsRector extends AbstractRector
{
    private const IMAGE_UTILITY_CLASS = 'Drupal\\Component\\Utility\\Image';
    private const RESPONSIVE_IMAGE_BUILDER = 'Drupal\\responsive_image\\ResponsiveImageBuilder';

    /** Map of function name => [FQCN, method] for static-call replacements. */
    private const STATIC_REPLACEMENTS = [
        'image_filter_keyword' => [self::IMAGE_UTILITY_CLASS, 'getKeywordOffset'],
    ];

    /** Map of function name => ResponsiveImageBuilder method name. */
    private const SERVICE_REPLACEMENTS = [
        '_responsive_image_build_source_attributes' => 'buildSourceAttributes',
        'responsive_image_get_image_dimensions'     => 'getImageDimensions',
        'responsive_image_get_mime_type'            => 'getMimeType',
        '_responsive_image_image_style_url'         => 'getImageStyleUrl',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace removed procedural functions from image and responsive_image modules with their drupal:12.0.0 equivalents',
            [
                new CodeSample(
                    'image_filter_keyword($anchor, $current_size, $new_size);',
                    '\\Drupal\\Component\\Utility\\Image::getKeywordOffset($anchor, $current_size, $new_size);'
                ),
                new CodeSample(
                    'responsive_image_get_mime_type($image_style_name, $extension);',
                    '\\Drupal::service(\\Drupal\\responsive_image\\ResponsiveImageBuilder::class)->getMimeType($image_style_name, $extension);'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof FuncCall || !$node->name instanceof Name) {
            return null;
        }

        $funcName = $node->name->toString();

        // image_filter_keyword() => Image::getKeywordOffset()
        if (isset(self::STATIC_REPLACEMENTS[$funcName])) {
            [$class, $method] = self::STATIC_REPLACEMENTS[$funcName];
            return new StaticCall(
                new FullyQualified($class),
                $method,
                $node->args
            );
        }

        // responsive_image_*() => \Drupal::service(ResponsiveImageBuilder::class)->method()
        if (isset(self::SERVICE_REPLACEMENTS[$funcName])) {
            $serviceCall = new StaticCall(
                new FullyQualified('Drupal'),
                'service',
                [new Arg(new ClassConstFetch(
                    new FullyQualified(self::RESPONSIVE_IMAGE_BUILDER),
                    'class'
                ))]
            );

            return new MethodCall(
                $serviceCall,
                self::SERVICE_REPLACEMENTS[$funcName],
                $node->args
            );
        }

        return null;
    }
}
