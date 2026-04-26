<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3440169
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites deprecated drupalGet() $headers argument patterns deprecated
// in Drupal 11.1.0. Integer-keyed colon-separated strings like
// ['X-Requested-With: XMLHttpRequest'] are split into ['X-Requested-
// With' => 'XMLHttpRequest']. Null header values like ['Accept-Language'
// => NULL] are replaced with empty strings. See change records
// https://www.drupal.org/node/3456178 and
// https://www.drupal.org/node/3456233.
//
// Before:
//   $this->drupalGet('/path', [], ['X-Requested-With: XMLHttpRequest']);
//   $this->drupalGet('', [], ['Accept-Language' => NULL]);
//
// After:
//   $this->drupalGet('/path', [], ['X-Requested-With' => 'XMLHttpRequest']);
//   $this->drupalGet('', [], ['Accept-Language' => '']);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites deprecated drupalGet() $headers argument formats:
 *  - Integer-keyed 'Header-Name: value' strings => ['Header-Name' => 'value']
 *  - Null header values => empty string ''
 */
final class DrupalGetHeadersAssocArrayRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Convert drupalGet() $headers from indexed colon-separated strings or null values to an associative array, as required by Drupal 11.1.0.',
            [
                new CodeSample(
                    '$this->drupalGet(\'/path\', [], [\'X-Requested-With: XMLHttpRequest\']);',
                    '$this->drupalGet(\'/path\', [], [\'X-Requested-With\' => \'XMLHttpRequest\']);'
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
        if (!$this->isName($node->name, 'drupalGet')) {
            return null;
        }

        // $headers is the third argument (index 2).
        if (count($node->args) < 3) {
            return null;
        }

        $headersArg = $node->args[2];
        if (!$headersArg instanceof Arg) {
            return null;
        }

        $headersArray = $headersArg->value;
        if (!$headersArray instanceof Array_) {
            return null;
        }

        $changed = false;

        foreach ($headersArray->items as $item) {
            if (!$item instanceof ArrayItem) {
                continue;
            }

            // Pattern 1: integer-keyed item with 'Header-Name: value' string.
            // Deprecated since drupal:11.1.0 — see https://www.drupal.org/node/3456178
            if ($item->key === null && $item->value instanceof String_) {
                $raw = $item->value->value;
                if (str_contains($raw, ':')) {
                    [$headerName, $headerValue] = explode(':', $raw, 2);
                    $item->key   = new String_(trim($headerName));
                    $item->value = new String_(trim($headerValue));
                    $changed = true;
                    continue;
                }
            }

            // Pattern 2: null header value.
            // Deprecated since drupal:11.1.0 — see https://www.drupal.org/node/3456233
            if ($item->value instanceof ConstFetch
                && strtolower((string) $item->value->name) === 'null'
            ) {
                $item->value = new String_('');
                $changed = true;
            }
        }

        return $changed ? $node : null;
    }
}
