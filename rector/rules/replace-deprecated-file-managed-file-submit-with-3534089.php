<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3534089
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.3 deprecated the global file_managed_file_submit() form
// submit handler, moving its logic to
// \Drupal\file\Element\ManagedFile::submit(). This rule rewrites both
// direct function calls and the far more common string-callback form
// '#submit' => ['file_managed_file_submit'] to the equivalent static
// method callable, preventing fatal errors in Drupal 12.
//
// Before:
//   $form['upload']['#submit'] = ['file_managed_file_submit'];
//   $form['actions']['#submit'][] = 'file_managed_file_submit';
//   file_managed_file_submit($form, $form_state);
//
// After:
//   $form['upload']['#submit'] = [[\Drupal\file\Element\ManagedFile::class, 'submit']];
//   $form['actions']['#submit'][] = [\Drupal\file\Element\ManagedFile::class, 'submit'];
//   \Drupal\file\Element\ManagedFile::submit($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated file_managed_file_submit() with
 * \Drupal\file\Element\ManagedFile::submit().
 *
 * Handles both direct function calls and string callbacks in #submit arrays.
 */
final class FileManagedFileSubmitRector extends AbstractRector
{
    private const DEPRECATED_FUNCTION = 'file_managed_file_submit';
    private const NEW_CLASS = 'Drupal\\file\\Element\\ManagedFile';
    private const NEW_METHOD = 'submit';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated file_managed_file_submit() with \\Drupal\\file\\Element\\ManagedFile::submit()',
            [
                new CodeSample(
                    "\$form['upload']['#submit'] = ['file_managed_file_submit'];",
                    "\$form['upload']['#submit'] = [[\\Drupal\\file\\Element\\ManagedFile::class, 'submit']];"
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [FuncCall::class, String_::class];
    }

    /**
     * @param FuncCall|String_ $node
     */
    public function refactor(Node $node): ?Node
    {
        // Case 1: Direct function call – file_managed_file_submit($form, $form_state)
        if ($node instanceof FuncCall) {
            if (!$this->isName($node, self::DEPRECATED_FUNCTION)) {
                return null;
            }
            return new Node\Expr\StaticCall(
                new FullyQualified(self::NEW_CLASS),
                self::NEW_METHOD,
                $node->args
            );
        }

        // Case 2: String callback – 'file_managed_file_submit' inside an array
        if ($node instanceof String_) {
            if ($node->value !== self::DEPRECATED_FUNCTION) {
                return null;
            }
            // Replace the string with [\Drupal\file\Element\ManagedFile::class, 'submit']
            return new Array_([
                new ArrayItem(
                    new ClassConstFetch(
                        new FullyQualified(self::NEW_CLASS),
                        'class'
                    )
                ),
                new ArrayItem(
                    new String_(self::NEW_METHOD)
                ),
            ]);
        }

        return null;
    }
}
