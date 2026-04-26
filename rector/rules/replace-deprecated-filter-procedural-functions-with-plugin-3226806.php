<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3226806
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces _filter_autop(), _filter_html_escape(), and
// _filter_html_image_secure_process() — all deprecated in drupal:11.4.0
// and removed in drupal:13.0.0 — with equivalent plugin.manager.filter
// createInstance() chains. The logic for each was moved into the
// corresponding filter plugin class, and no direct API replacement was
// provided, so this rule reconstructs the canonical call via the service
// container.
//
// Before:
//   _filter_autop($text)
//
// After:
//   \Drupal::service('plugin.manager.filter')->createInstance('filter_autop')->process($text, \Drupal::languageManager()->getCurrentLanguage()->getId())->getProcessedText()


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated filter procedural functions with plugin manager calls.
 *
 * Targets _filter_autop(), _filter_html_escape(), and
 * _filter_html_image_secure_process() deprecated in drupal:11.4.0.
 * Each is rewritten to the equivalent plugin.manager.filter createInstance()
 * chain.
 */
final class DeprecatedFilterFunctionsRector extends AbstractRector
{
    /**
     * Maps deprecated function name to filter plugin ID.
     *
     * @var array<string, string>
     */
    private const FUNCTION_TO_PLUGIN_ID = [
        '_filter_autop' => 'filter_autop',
        '_filter_html_escape' => 'filter_html_escape',
        '_filter_html_image_secure_process' => 'filter_html_image_secure',
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated _filter_autop(), _filter_html_escape(), and _filter_html_image_secure_process() with plugin manager calls.',
            [
                new CodeSample(
                    '_filter_autop($text)',
                    "\\Drupal::service('plugin.manager.filter')->createInstance('filter_autop')->process(\$text, \\Drupal::languageManager()->getCurrentLanguage()->getId())->getProcessedText()"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /** @param FuncCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node->name instanceof Name) {
            return null;
        }

        $funcName = $this->getName($node->name);

        if (!isset(self::FUNCTION_TO_PLUGIN_ID[$funcName])) {
            return null;
        }

        if (count($node->args) < 1) {
            return null;
        }

        $pluginId = self::FUNCTION_TO_PLUGIN_ID[$funcName];

        // The text argument (first arg of the deprecated function).
        $textArg = $node->args[0];
        $textExpr = $textArg instanceof Arg ? $textArg->value : $textArg;

        // Build: \Drupal::languageManager()->getCurrentLanguage()->getId()
        $drupalLanguageManager = $this->nodeFactory->createStaticCall('Drupal', 'languageManager');
        $getCurrentLanguage = $this->nodeFactory->createMethodCall($drupalLanguageManager, 'getCurrentLanguage');
        $getLangcodeExpr = $this->nodeFactory->createMethodCall($getCurrentLanguage, 'getId');

        // Build: \Drupal::service('plugin.manager.filter')
        $drupalService = $this->nodeFactory->createStaticCall('Drupal', 'service', [
            new String_('plugin.manager.filter'),
        ]);

        // ->createInstance('filter_autop') (or other plugin id)
        $createInstance = $this->nodeFactory->createMethodCall($drupalService, 'createInstance', [
            new String_($pluginId),
        ]);

        // ->process($text, $langcode)
        $process = $this->nodeFactory->createMethodCall($createInstance, 'process', [
            $textExpr,
            $getLangcodeExpr,
        ]);

        // ->getProcessedText()
        return $this->nodeFactory->createMethodCall($process, 'getProcessedText');
    }
}
