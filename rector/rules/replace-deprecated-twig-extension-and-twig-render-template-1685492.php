<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Drupal 11.3 deprecated the global .engine hook functions
 * twig_extension() and twig_render_template() in favour of the new
 * ThemeEngineInterface and its TwigThemeEngine service implementation.
 * twig_extension() is replaced with the literal '.html.twig';
 * twig_render_template() is replaced with a call to
 * \Drupal::service(TwigThemeEngine::class)->renderTemplate(). Both are
 * removed in Drupal 12.
 *
 * Before:
 *   $ext = twig_extension();
 *   $output = twig_render_template($template_file, $variables);
 *
 * After:
 *   $ext = '.html.twig';
 *   $output = \Drupal::service(\Drupal\Core\Template\TwigThemeEngine::class)->renderTemplate($template_file, $variables);
 *
 * @see https://www.drupal.org/node/1685492
 * @deprecated drupal:11.3.0
 * @removed drupal:12.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated twig_extension() and twig_render_template() calls.
 *
 * Drupal 11.3 deprecated the global theme-engine hook functions in twig.engine
 * in favour of the TwigThemeEngine service which implements ThemeEngineInterface.
 */
final class TwigEngineFunctionsRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated twig_extension() and twig_render_template() calls with TwigThemeEngine service equivalents',
            [
                new CodeSample(
                    '$ext = twig_extension();',
                    "\$ext = '.html.twig';"
                ),
                new CodeSample(
                    '$output = twig_render_template($template_file, $variables);',
                    "\$output = \\Drupal::service(\\Drupal\\Core\\Template\\TwigThemeEngine::class)->renderTemplate(\$template_file, \$variables);"
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
        $name = $this->getName($node);

        if ($name === 'twig_extension') {
            return new String_('.html.twig');
        }

        if ($name === 'twig_render_template') {
            // Build: \Drupal::service(\Drupal\Core\Template\TwigThemeEngine::class)
            $drupalService = new MethodCall(
                new StaticCall(
                    new Node\Name\FullyQualified('Drupal'),
                    'service',
                    [new Node\Arg(
                        new \PhpParser\Node\Expr\ClassConstFetch(
                            new Node\Name\FullyQualified('Drupal\Core\Template\TwigThemeEngine'),
                            'class'
                        )
                    )]
                ),
                'renderTemplate',
                $node->args
            );
            return $drupalService;
        }

        return null;
    }
}
