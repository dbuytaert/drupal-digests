<?php

declare(strict_types=1);

// Source: https://www.drupal.org/node/3571054
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Rewrites the removed $dialog_options['dialogClass'] key to
// $dialog_options['classes']['ui-dialog'] in new OpenDialogCommand(...)
// and new OpenOffCanvasDialogCommand(...) calls. The dialogClass option
// was deprecated in drupal:10.3.0 and removed in drupal:12.0.0 (issue
// #3571054, change record #3440844). Handles merging when classes['ui-
// dialog'] is already present.
//
// Before:
//   new \Drupal\Core\Ajax\OpenDialogCommand('#my-dialog', 'Title', $content, ['dialogClass' => 'my-class', 'width' => 600]);
//
// After:
//   new \Drupal\Core\Ajax\OpenDialogCommand('#my-dialog', 'Title', $content, ['width' => 600, 'classes' => ['ui-dialog' => 'my-class']]);


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Rector\AbstractRector;
use Rector\Config\RectorConfig;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rewrites the removed $dialog_options['dialogClass'] key to
 * $dialog_options['classes']['ui-dialog'] for OpenDialogCommand and
 * OpenOffCanvasDialogCommand constructors.
 *
 * The 'dialogClass' option was deprecated in drupal:10.3.0 and removed in
 * drupal:12.0.0 (issue #3571054, change record #3440844). Callers that
 * pass a literal array to the $dialog_options argument must use
 * $dialog_options['classes']['ui-dialog'] instead.
 *
 * @see https://www.drupal.org/node/3440844
 * @see https://www.drupal.org/project/drupal/issues/3571054
 */
final class ReplaceDialogClassOptionRector extends AbstractRector
{
    /**
     * Map: FQCN => zero-based index of the $dialog_options argument.
     */
    private const CLASS_ARG_INDEX = [
        'Drupal\\Core\\Ajax\\OpenDialogCommand'          => 3,
        'Drupal\\Core\\Ajax\\OpenOffCanvasDialogCommand' => 2,
    ];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace removed \$dialog_options['dialogClass'] with \$dialog_options['classes']['ui-dialog'] in OpenDialogCommand / OpenOffCanvasDialogCommand constructors (removed in drupal:12.0.0)",
            [
                new CodeSample(
                    "new \\Drupal\\Core\\Ajax\\OpenDialogCommand('#my-dialog', 'Title', \$content, ['dialogClass' => 'my-class']);",
                    "new \\Drupal\\Core\\Ajax\\OpenDialogCommand('#my-dialog', 'Title', \$content, ['classes' => ['ui-dialog' => 'my-class']]);"
                ),
                new CodeSample(
                    "new \\Drupal\\Core\\Ajax\\OpenOffCanvasDialogCommand('Title', \$content, ['dialogClass' => 'my-class']);",
                    "new \\Drupal\\Core\\Ajax\\OpenOffCanvasDialogCommand('Title', \$content, ['classes' => ['ui-dialog' => 'my-class']]);"
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [New_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (!$node instanceof New_) {
            return null;
        }

        $className = $this->getClassName($node);
        if ($className === null) {
            return null;
        }

        if (!isset(self::CLASS_ARG_INDEX[$className])) {
            return null;
        }

        $argIndex = self::CLASS_ARG_INDEX[$className];

        if (!isset($node->args[$argIndex])) {
            return null;
        }

        $arg = $node->args[$argIndex];
        if (!$arg instanceof \PhpParser\Node\Arg) {
            return null;
        }

        $optionsArray = $arg->value;
        if (!$optionsArray instanceof Array_) {
            return null;
        }

        $dialogClassIdx = null;
        $classesIdx = null;
        $uiDialogIdx = null;

        foreach ($optionsArray->items as $idx => $item) {
            if (!$item instanceof ArrayItem || !$item->key instanceof String_) {
                continue;
            }

            if ($item->key->value === 'dialogClass') {
                $dialogClassIdx = $idx;
            } elseif ($item->key->value === 'classes') {
                $classesIdx = $idx;
                if ($item->value instanceof Array_) {
                    foreach ($item->value->items as $subIdx => $subItem) {
                        if ($subItem instanceof ArrayItem
                            && $subItem->key instanceof String_
                            && $subItem->key->value === 'ui-dialog'
                        ) {
                            $uiDialogIdx = $subIdx;
                        }
                    }
                }
            }
        }

        if ($dialogClassIdx === null) {
            return null;
        }

        $dialogClassValue = $optionsArray->items[$dialogClassIdx]->value;

        unset($optionsArray->items[$dialogClassIdx]);
        $optionsArray->items = array_values($optionsArray->items);

        if ($classesIdx === null) {
            $optionsArray->items[] = new ArrayItem(
                new Array_([
                    new ArrayItem($dialogClassValue, new String_('ui-dialog')),
                ]),
                new String_('classes')
            );
        } elseif ($uiDialogIdx !== null) {
            $classesItem = $optionsArray->items[$classesIdx];
            if (!$classesItem instanceof ArrayItem || !$classesItem->value instanceof Array_) {
                return null;
            }

            $uiDialogItem = $classesItem->value->items[$uiDialogIdx];
            if (!$uiDialogItem instanceof ArrayItem) {
                return null;
            }

            if (!($uiDialogItem->value instanceof String_) || !($dialogClassValue instanceof String_)) {
                return null;
            }

            $uiDialogItem->value = new String_(
                $uiDialogItem->value->value . ' ' . $dialogClassValue->value
            );
        } else {
            $classesItem = $optionsArray->items[$classesIdx];
            if (!$classesItem instanceof ArrayItem || !$classesItem->value instanceof Array_) {
                return null;
            }

            $classesItem->value->items[] = new ArrayItem(
                $dialogClassValue,
                new String_('ui-dialog')
            );
        }

        return $node;
    }

    private function getClassName(New_ $node): ?string
    {
        $class = $node->class;

        if ($class instanceof FullyQualified) {
            return $class->toString();
        }

        if ($class instanceof \PhpParser\Node\Name) {
            $resolved = $class->getAttribute('resolvedName');
            if ($resolved instanceof FullyQualified) {
                return $resolved->toString();
            }

            $str = $class->toString();
            foreach (array_keys(self::CLASS_ARG_INDEX) as $fqcn) {
                $short = substr($fqcn, (int) strrpos($fqcn, '\\') + 1);
                if ($str === $short || $str === $fqcn) {
                    return $fqcn;
                }
            }
        }

        return null;
    }
}
