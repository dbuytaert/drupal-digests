<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3366561
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Constructing HtmlResponseAttachmentsProcessor or
// BigPipeResponseAttachmentsProcessor without a
// FileUrlGeneratorInterface argument is deprecated in drupal:11.4.0 and
// will be required in drupal:12.0.0. This rule detects direct new
// instantiations missing the final argument and appends
// \Drupal::service('file_url_generator') automatically.
//
// Before:
//   new \Drupal\Core\Render\HtmlResponseAttachmentsProcessor(
//       $assetResolver, $configFactory, $cssRenderer, $jsRenderer,
//       $requestStack, $renderer, $moduleHandler, $languageManager
//   );
//
// After:
//   new \Drupal\Core\Render\HtmlResponseAttachmentsProcessor(
//       $assetResolver, $configFactory, $cssRenderer, $jsRenderer,
//       $requestStack, $renderer, $moduleHandler, $languageManager,
//       \Drupal::service('file_url_generator')
//   );


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the FileUrlGeneratorInterface argument to HtmlResponseAttachmentsProcessor
 * and BigPipeResponseAttachmentsProcessor constructors when it is missing.
 *
 * drupal:11.4.0 deprecated constructing these classes without the new argument.
 * drupal:12.0.0 will make it required.
 */
final class AddFileUrlGeneratorToAttachmentsProcessorRector extends AbstractRector
{
    /**
     * Maps fully-qualified class name to expected total argument count (new API).
     */
    private const CLASS_ARG_COUNTS = [
        'Drupal\\Core\\Render\\HtmlResponseAttachmentsProcessor'        => 9,
        'Drupal\\big_pipe\\Render\\BigPipeResponseAttachmentsProcessor'  => 10,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the FileUrlGeneratorInterface argument to HtmlResponseAttachmentsProcessor and BigPipeResponseAttachmentsProcessor constructors deprecated in drupal:11.4.0.',
            [
                new CodeSample(
                    'new \\Drupal\\Core\\Render\\HtmlResponseAttachmentsProcessor($assetResolver, $configFactory, $cssRenderer, $jsRenderer, $requestStack, $renderer, $moduleHandler, $languageManager);',
                    'new \\Drupal\\Core\\Render\\HtmlResponseAttachmentsProcessor($assetResolver, $configFactory, $cssRenderer, $jsRenderer, $requestStack, $renderer, $moduleHandler, $languageManager, \\Drupal::service(\'file_url_generator\'));'
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
        foreach (self::CLASS_ARG_COUNTS as $fqcn => $expectedCount) {
            if (!$this->isName($node->class, $fqcn)) {
                continue;
            }
            // Already has the expected number of arguments (or more): skip.
            if (count($node->args) >= $expectedCount) {
                return null;
            }
            // Append \Drupal::service('file_url_generator') as the final argument.
            $serviceCall = $this->nodeFactory->createStaticCall(
                'Drupal',
                'service',
                [new Arg(new String_('file_url_generator'))]
            );
            $node->args[] = new Arg($serviceCall);
            return $node;
        }
        return null;
    }
}
