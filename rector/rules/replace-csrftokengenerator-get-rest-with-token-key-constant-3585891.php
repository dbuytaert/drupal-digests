<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces CsrfTokenGenerator::get('rest') with
 * CsrfTokenGenerator::get(CsrfRequestHeaderAccessCheck::TOKEN_KEY). The
 * 'rest' key for CSRF token generation was deprecated in drupal:11.4.0
 * and will be removed in drupal:12.0.0; old sessions using that key are
 * no longer supported. Contrib modules that implement custom login or
 * REST authentication flows by generating tokens with the old key
 * benefit from this rule.
 *
 * Before:
 *   $this->csrfToken->get('rest');
 *
 * After:
 *   $this->csrfToken->get(\Drupal\Core\Access\CsrfRequestHeaderAccessCheck::TOKEN_KEY);
 *
 * Caveats:
 *   Only fires when the receiver is typed as CsrfTokenGenerator (the
 *   concrete class, which has no interface). Untyped or dynamically-
 *   resolved CSRF service calls (e.g. via
 *   \Drupal::service('csrf_token')->get('rest')) are not matched
 *   because PHPStan cannot infer the receiver type from a generic
 *   service-locator call.
 *
 * @see https://www.drupal.org/node/3585891
 * @deprecated drupal:11.4.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CsrfTokenGetRestKeyRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace CsrfTokenGenerator::get('rest') with get(CsrfRequestHeaderAccessCheck::TOKEN_KEY), deprecated in drupal:11.4.0.",
            [new CodeSample(
                "\$this->csrfToken->get('rest');",
                "\$this->csrfToken->get(\Drupal\Core\Access\CsrfRequestHeaderAccessCheck::TOKEN_KEY);",
            )],
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof MethodCall) {
            return null;
        }
        if (!$this->isName($node->name, 'get')) {
            return null;
        }
        if (!$this->isObjectType($node->var, new ObjectType('Drupal\Core\Access\CsrfTokenGenerator'))) {
            return null;
        }
        if (count($node->args) !== 1) {
            return null;
        }
        $firstArg = $node->args[0];
        if (!$firstArg instanceof Arg) {
            return null;
        }
        if (!$firstArg->value instanceof String_) {
            return null;
        }
        if ($firstArg->value->value !== 'rest') {
            return null;
        }
        $node->args[0] = new Arg(
            new ClassConstFetch(
                new FullyQualified('Drupal\Core\Access\CsrfRequestHeaderAccessCheck'),
                'TOKEN_KEY'
            )
        );
        return $node;
    }
}
