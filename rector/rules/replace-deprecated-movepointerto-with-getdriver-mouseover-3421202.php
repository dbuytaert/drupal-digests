<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3421202
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites $this->movePointerTo('#id') calls deprecated in drupal:11.1.0
// and removed in drupal:12.0.0. The replacement is
// $this->getSession()->getDriver()->mouseOver(), which requires an XPath
// locator instead of a CSS selector. This rule handles the common case
// of simple CSS ID selectors (#foo), converting them to the XPath
// equivalent .//*[@id="foo"] automatically.
//
// Before:
//   $this->movePointerTo('#my-element');
//
// After:
//   $this->getSession()->getDriver()->mouseOver('.//*[@id="my-element"]');


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated movePointerTo() with getSession()->getDriver()->mouseOver().
 *
 * movePointerTo() is deprecated in drupal:11.1.0 and removed in drupal:12.0.0.
 * It accepted a CSS selector; the replacement mouseOver() requires an XPath
 * expression. This rule handles the common case of simple CSS ID selectors
 * (#foo), converting them to the equivalent XPath (.//*[@id="foo"]).
 */
final class MovePointerToMouseOverRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated movePointerTo() with getSession()->getDriver()->mouseOver()',
            [
                new CodeSample(
                    '$this->movePointerTo(\'#my-element\');',
                    '$this->getSession()->getDriver()->mouseOver(\'.//*[@id="my-element"]\'  );'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Only match $this->movePointerTo(...)
        if (!$node->var instanceof Variable) {
            return null;
        }
        if ($this->getName($node->var) !== 'this') {
            return null;
        }
        if (!$this->isName($node->name, 'movePointerTo')) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }

        $arg = $node->args[0]->value;

        // Only handle string literals with a simple CSS ID selector (#foo).
        // Other selector types cannot be auto-converted to XPath safely.
        if (!$arg instanceof String_) {
            return null;
        }

        $cssSelector = $arg->value;

        // Match simple CSS ID selectors: #identifier (letters, digits, _ and -)
        if (!preg_match('/^#([a-zA-Z][a-zA-Z0-9_-]*)$/', $cssSelector, $matches)) {
            return null;
        }

        $xpathSelector = './/*[@id="' . $matches[1] . '"]';

        // Build: $this->getSession()->getDriver()->mouseOver($xpathSelector)
        $getSession = new MethodCall($node->var, 'getSession', []);
        $getDriver = new MethodCall($getSession, 'getDriver', []);

        return new MethodCall(
            $getDriver,
            'mouseOver',
            [new Arg(new String_($xpathSelector))]
        );
    }
}
