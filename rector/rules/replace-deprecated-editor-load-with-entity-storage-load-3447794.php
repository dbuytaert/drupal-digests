<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3447794
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated editor_load($format_id) function
// (deprecated in drupal:11.2.0, removed in drupal:12.0.0) with the
// canonical replacement
// \Drupal::entityTypeManager()->getStorage('editor')->load($format_id).
// The old helper loaded all editor config entities at once via
// Editor::loadMultiple(), which triggers a costly
// ConfigFactory::listAll() on warm caches; the new form loads only the
// requested entity.
//
// Before:
//   $editor = editor_load($format_id);
//
// After:
//   $editor = \Drupal::entityTypeManager()->getStorage('editor')->load($format_id);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces deprecated editor_load() with the entity storage equivalent.
 */
final class EditorLoadDeprecationRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            "Replace deprecated editor_load(\$format_id) with \\Drupal::entityTypeManager()->getStorage('editor')->load(\$format_id)",
            [
                new CodeSample(
                    "\$editor = editor_load(\$format_id);",
                    "\$editor = \\Drupal::entityTypeManager()->getStorage('editor')->load(\$format_id);"
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
        if (!$this->isName($node, 'editor_load')) {
            return null;
        }

        // Build: \Drupal::entityTypeManager()->getStorage('editor')->load($format_id)
        $drupalClass = new FullyQualified('Drupal');

        // \Drupal::entityTypeManager()
        $entityTypeManagerCall = new StaticCall(
            $drupalClass,
            'entityTypeManager',
            []
        );

        // ->getStorage('editor')
        $getStorageCall = new MethodCall(
            $entityTypeManagerCall,
            'getStorage',
            [new Node\Arg(new String_('editor'))]
        );

        // ->load($format_id) — preserve the original argument
        $originalArg = $node->args[0] ?? new Node\Arg(new Node\Expr\ConstFetch(new Name('null')));

        return new MethodCall(
            $getStorageCall,
            'load',
            [$originalArg]
        );
    }
}
