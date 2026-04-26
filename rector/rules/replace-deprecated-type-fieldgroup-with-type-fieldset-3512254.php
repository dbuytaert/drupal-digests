<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3512254
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// The Fieldgroup render element (#type => 'fieldgroup') is deprecated in
// drupal:11.2.0 and removed in drupal:12.0.0. This rule rewrites render
// arrays to use #type => 'fieldset' instead. Note that if your code
// relies on the fieldgroup CSS class or the core/drupal.fieldgroup
// library being auto-attached, those must be added manually after the
// migration.
//
// Before:
//   $form['account'] = [
//     '#type' => 'fieldgroup',
//     '#title' => $this->t('Account settings'),
//   ];
//
// After:
//   $form['account'] = [
//     '#type' => 'fieldset',
//     '#title' => $this->t('Account settings'),
//   ];


use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces '#type' => 'fieldgroup' with '#type' => 'fieldset' in render arrays.
 *
 * The Fieldgroup render element is deprecated in drupal:11.2.0 and removed in
 * drupal:12.0.0. Use Fieldset instead.
 *
 * @see https://www.drupal.org/node/3515272
 */
final class FieldgroupToFieldsetRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace '#type' => 'fieldgroup' with '#type' => 'fieldset' in render arrays (Drupal deprecation).",
            [
                new CodeSample(
                    <<<'CODE'
$form['account'] = [
  '#type' => 'fieldgroup',
  '#title' => $this->t('Account settings'),
];
CODE,
                    <<<'CODE'
$form['account'] = [
  '#type' => 'fieldset',
  '#title' => $this->t('Account settings'),
];
CODE
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Array_::class];
    }

    /** @param Array_ $node */
    public function refactor(Node $node): ?Node
    {
        $changed = false;

        foreach ($node->items as $item) {
            if ($item === null) {
                continue;
            }

            // Key must be the string '#type'.
            if (!$item->key instanceof String_ || $item->key->value !== '#type') {
                continue;
            }

            // Value must be the string 'fieldgroup'.
            if (!$item->value instanceof String_ || $item->value->value !== 'fieldgroup') {
                continue;
            }

            $item->value = new String_('fieldset');
            $changed = true;
        }

        return $changed ? $node : null;
    }
}
