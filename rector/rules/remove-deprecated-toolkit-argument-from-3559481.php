<?php

declare(strict_types=1);

/**
 * Drupal Digests (https://github.com/dbuytaert/drupal-digests)
 * by Dries Buytaert (https://dri.es)
 *
 * In drupal:11.4.0, ImageToolkitOperationBase::__construct() deprecated
 * its ImageToolkitInterface $toolkit 4th argument. The plugin manager
 * now injects the toolkit via setToolkit() after instantiation, enabling
 * constructor autowiring. This rule removes the $toolkit parameter from
 * subclass constructors and strips it from the corresponding
 * parent::__construct() call.
 *
 * Before:
 *   use Drupal\Core\ImageToolkit\ImageToolkitInterface;
 *   use Drupal\Core\ImageToolkit\ImageToolkitOperationBase;
 *   use Psr\Log\LoggerInterface;
 *   
 *   class MyOperation extends ImageToolkitOperationBase {
 *       public function __construct(array $configuration, $plugin_id, array $plugin_definition, ImageToolkitInterface $toolkit, LoggerInterface $logger) {
 *           parent::__construct($configuration, $plugin_id, $plugin_definition, $toolkit, $logger);
 *       }
 *   }
 *
 * After:
 *   use Drupal\Core\ImageToolkit\ImageToolkitOperationBase;
 *   use Psr\Log\LoggerInterface;
 *   
 *   class MyOperation extends ImageToolkitOperationBase {
 *       public function __construct(array $configuration, $plugin_id, array $plugin_definition, LoggerInterface $logger) {
 *           parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
 *       }
 *   }
 *
 * Caveats:
 *   Only transforms when $toolkit appears exactly once in the
 *   constructor body (as the parent::__construct() argument). If
 *   $toolkit is also used directly in the constructor body, the rule
 *   skips that class to avoid breaking code that relies on the variable
 *   before setToolkit() can be called.
 *
 * @see https://www.drupal.org/node/3559481
 * @deprecated drupal:11.4.0
 * @removed drupal:13.0.0
 */


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Removes the deprecated $toolkit argument from ImageToolkitOperationBase::__construct().
 *
 * Before drupal:11.4.0, subclasses passed ImageToolkitInterface $toolkit as the
 * 4th constructor argument. This is deprecated; the toolkit is now injected via
 * setToolkit() by the plugin manager.
 */
final class RemoveToolkitArgFromImageToolkitOperationConstructorRector extends AbstractRector
{
    private const TOOLKIT_INTERFACE = 'Drupal\\Core\\ImageToolkit\\ImageToolkitInterface';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove deprecated $toolkit argument from ImageToolkitOperationBase::__construct(). The toolkit is now injected via setToolkit() by the plugin manager.',
            [new CodeSample(
                <<<'CODE'
use Drupal\Core\ImageToolkit\ImageToolkitInterface;
use Drupal\Core\ImageToolkit\ImageToolkitOperationBase;
use Psr\Log\LoggerInterface;

class MyOperation extends ImageToolkitOperationBase {
    public function __construct(array $configuration, $plugin_id, array $plugin_definition, ImageToolkitInterface $toolkit, LoggerInterface $logger) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $toolkit, $logger);
    }
}
CODE,
                <<<'CODE'
use Drupal\Core\ImageToolkit\ImageToolkitOperationBase;
use Psr\Log\LoggerInterface;

class MyOperation extends ImageToolkitOperationBase {
    public function __construct(array $configuration, $plugin_id, array $plugin_definition, LoggerInterface $logger) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $logger);
    }
}
CODE
            )]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        $constructor = $node->getMethod('__construct');
        if ($constructor === null) {
            return null;
        }

        $params = $constructor->params;

        // We need at least 5 params (configuration, plugin_id, plugin_definition, toolkit, logger).
        if (count($params) < 5) {
            return null;
        }

        // Verify the 4th param (index 3) is typed as ImageToolkitInterface.
        $toolkitParam = $params[3];
        if ($toolkitParam->type === null) {
            return null;
        }

        $typeName = $this->getName($toolkitParam->type);
        if ($typeName !== self::TOOLKIT_INTERFACE) {
            return null;
        }

        $toolkitVarName = $this->getName($toolkitParam->var);
        if ($toolkitVarName === null) {
            return null;
        }

        // Count all usages of the $toolkit variable inside the constructor body
        // to ensure it is only passed to parent::__construct().
        $toolkitUsageCount = 0;
        $this->traverseNodesWithCallable($constructor->stmts ?? [], function (Node $innerNode) use ($toolkitVarName, &$toolkitUsageCount): void {
            if ($innerNode instanceof Variable && $this->isName($innerNode, $toolkitVarName)) {
                $toolkitUsageCount++;
            }
        });

        // If $toolkit is used more than once or not at all in body, skip.
        if ($toolkitUsageCount !== 1) {
            return null;
        }

        // Locate parent::__construct() and confirm $toolkit is its 4th arg,
        // then remove that arg.
        $parentCallUpdated = false;
        $this->traverseNodesWithCallable($constructor->stmts ?? [], function (Node $innerNode) use ($toolkitVarName, &$parentCallUpdated): ?Node {
            if (!$innerNode instanceof StaticCall) {
                return null;
            }
            if (!$this->isName($innerNode->class, 'parent') || !$this->isName($innerNode->name, '__construct')) {
                return null;
            }
            if (!isset($innerNode->args[3]) || !$innerNode->args[3] instanceof Arg) {
                return null;
            }
            $arg3Value = $innerNode->args[3]->value;
            if (!$arg3Value instanceof Variable || !$this->isName($arg3Value, $toolkitVarName)) {
                return null;
            }
            array_splice($innerNode->args, 3, 1);
            $parentCallUpdated = true;
            return $innerNode;
        });

        if (!$parentCallUpdated) {
            return null;
        }

        // Remove the $toolkit parameter from the constructor signature.
        array_splice($constructor->params, 3, 1);

        return $node;
    }
}
