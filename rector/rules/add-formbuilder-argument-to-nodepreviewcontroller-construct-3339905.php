<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3339905
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Fixes the drupal:11.4.0 deprecation where
// NodePreviewController::__construct() is called without the new
// $formBuilder argument. The rule targets subclasses that call
// parent::__construct() with only 3 arguments as well as direct new
// NodePreviewController(...) instantiations with 3 arguments, appending
// \Drupal::service('form_builder') as the required 4th argument.
//
// Before:
//   parent::__construct($entity_type_manager, $renderer, $entity_repository);
//
// After:
//   parent::__construct($entity_type_manager, $renderer, $entity_repository, \Drupal::service('form_builder'));


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Adds the $formBuilder argument to NodePreviewController::__construct()
 * calls that omit it, resolving the deprecation introduced in drupal:11.4.0.
 */
final class NodePreviewControllerConstructorRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add the missing $formBuilder argument to parent::__construct() calls in subclasses of NodePreviewController, and to direct new NodePreviewController() instantiations, resolving the deprecation introduced in drupal:11.4.0.',
            [
                new CodeSample(
                    'parent::__construct($entity_type_manager, $renderer, $entity_repository);',
                    "parent::__construct(\$entity_type_manager, \$renderer, \$entity_repository, \\Drupal::service('form_builder'));"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class, New_::class];
    }

    /** @param Class_|New_ $node */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof New_) {
            return $this->refactorNew($node);
        }

        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }

        return null;
    }

    private function refactorNew(New_ $node): ?New_
    {
        $className = $this->getName($node->class);
        if ($className === null) {
            return null;
        }

        // Match both FQCN and short class name (use-imported).
        if ($className !== 'Drupal\\node\\Controller\\NodePreviewController'
            && $className !== 'NodePreviewController') {
            return null;
        }

        if (count($node->args) !== 3) {
            return null;
        }

        $node->args[] = new Arg(
            $this->nodeFactory->createStaticCall('Drupal', 'service', [
                new String_('form_builder'),
            ])
        );

        return $node;
    }

    private function refactorClass(Class_ $node): ?Class_
    {
        if ($node->extends === null) {
            return null;
        }

        $extendsName = $this->getName($node->extends);
        if ($extendsName === null) {
            return null;
        }

        // Match both FQCN (resolved by PHP-Parser NameResolver) and short name.
        if ($extendsName !== 'Drupal\\node\\Controller\\NodePreviewController'
            && $extendsName !== 'NodePreviewController') {
            return null;
        }

        $hasChanged = false;

        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use (&$hasChanged): ?StaticCall {
            if (!$subNode instanceof StaticCall) {
                return null;
            }

            if (!$this->isName($subNode->class, 'parent')) {
                return null;
            }

            if (!$this->isName($subNode->name, '__construct')) {
                return null;
            }

            if (count($subNode->args) !== 3) {
                return null;
            }

            $subNode->args[] = new Arg(
                $this->nodeFactory->createStaticCall('Drupal', 'service', [
                    new String_('form_builder'),
                ])
            );

            $hasChanged = true;

            return $subNode;
        });

        return $hasChanged ? $node : null;
    }
}
