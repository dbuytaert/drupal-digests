<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3567618
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.4.0 deprecated two procedural functions and a global
// constant from the image module. image_path_flush() becomes
// \Drupal::service(ImageDerivativeUtilities::class)->pathFlush(),
// image_style_options() becomes ->styleOptions() on the same service,
// and IMAGE_DERIVATIVE_TOKEN becomes
// \Drupal\image\ImageStyleInterface::TOKEN. All three are removed in
// Drupal 13.0.0.
//
// Before:
//   $token = IMAGE_DERIVATIVE_TOKEN;
//   image_path_flush('/path/to/image.jpg');
//   $options = image_style_options();
//   $options2 = image_style_options(FALSE);
//
// After:
//   $token = \Drupal\image\ImageStyleInterface::TOKEN;
//   \Drupal::service(\Drupal\image\ImageDerivativeUtilities::class)->pathFlush('/path/to/image.jpg');
//   $options = \Drupal::service(\Drupal\image\ImageDerivativeUtilities::class)->styleOptions();
//   $options2 = \Drupal::service(\Drupal\image\ImageDerivativeUtilities::class)->styleOptions(FALSE);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated image module functions and constant (Drupal 11.4.0).
 *
 * - IMAGE_DERIVATIVE_TOKEN  → \Drupal\image\ImageStyleInterface::TOKEN
 * - image_path_flush()      → \Drupal::service(ImageDerivativeUtilities::class)->pathFlush()
 * - image_style_options()   → \Drupal::service(ImageDerivativeUtilities::class)->styleOptions()
 */
final class DeprecatedImageFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated IMAGE_DERIVATIVE_TOKEN constant and image_path_flush() / image_style_options() procedural functions with their Drupal 11.4 service-based equivalents.',
            [
                new CodeSample(
                    '$token = IMAGE_DERIVATIVE_TOKEN;',
                    '$token = \\Drupal\\image\\ImageStyleInterface::TOKEN;'
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [Node\Expr\ConstFetch::class, FuncCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Node\Expr\ConstFetch) {
            if ($this->getName($node) === 'IMAGE_DERIVATIVE_TOKEN') {
                return new ClassConstFetch(
                    new FullyQualified('Drupal\\image\\ImageStyleInterface'),
                    'TOKEN'
                );
            }
            return null;
        }

        if ($node instanceof FuncCall) {
            $funcName = $this->getName($node);

            if ($funcName === 'image_path_flush') {
                return $this->buildServiceCall('pathFlush', $node->args);
            }

            if ($funcName === 'image_style_options') {
                return $this->buildServiceCall('styleOptions', $node->args);
            }
        }

        return null;
    }

    private function buildServiceCall(string $method, array $args): MethodCall
    {
        $classConst = new ClassConstFetch(
            new FullyQualified('Drupal\\image\\ImageDerivativeUtilities'),
            'class'
        );

        $serviceCall = new StaticCall(
            new FullyQualified('Drupal'),
            'service',
            [new Arg($classConst)]
        );

        return new MethodCall($serviceCall, $method, $args);
    }
}
