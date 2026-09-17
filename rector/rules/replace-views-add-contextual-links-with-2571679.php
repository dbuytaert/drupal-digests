<?php
declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * Replaces the deprecated procedural function
 * views_add_contextual_links() with a call to the new
 * \Drupal\views\ContextualLinksHelper::addLinks() service, introduced in
 * Drupal 11.4.0. The function is removed in Drupal 13.0.0. All argument
 * positions are preserved exactly, including the optional fourth
 * $view_element parameter.
 *
 * Before:
 *   views_add_contextual_links($element, 'view', $display_id);
 *
 * After:
 *   \Drupal::service(\Drupal\views\ContextualLinksHelper::class)->addLinks($element, 'view', $display_id);
 *
 * @see https://www.drupal.org/node/2571679
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated views_add_contextual_links() with the new service.
 */
final class ViewsAddContextualLinksRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated views_add_contextual_links() with \Drupal\views\ContextualLinksHelper::addLinks() service call',
            [
                new CodeSample(
                    'views_add_contextual_links($element, \'view\', $display_id);',
                    '\Drupal::service(\Drupal\views\ContextualLinksHelper::class)->addLinks($element, \'view\', $display_id);'
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
        if (!$this->isName($node, 'views_add_contextual_links')) {
            return null;
        }

        // Build ContextualLinksHelper::class
        $classConst = $this->nodeFactory->createClassConstReference('Drupal\views\ContextualLinksHelper');

        // Build \Drupal::service(ContextualLinksHelper::class)
        $serviceCall = $this->nodeFactory->createStaticCall('Drupal', 'service', [$classConst]);

        // Build ->addLinks(...original args...)
        return $this->nodeFactory->createMethodCall($serviceCall, 'addLinks', $node->args);
    }
}
