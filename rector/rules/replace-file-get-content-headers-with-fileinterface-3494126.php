<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3494126
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated procedural function
// file_get_content_headers($file) with the equivalent method call
// $file->getDownloadHeaders() on the file entity. The function was
// deprecated in Drupal 11.2.0 and will be removed in Drupal 12.0.0. The
// new method lives on Drupal\file\Entity\FileInterface and returns the
// same content headers array.
//
// Before:
//   file_get_content_headers($file);
//
// After:
//   $file->getDownloadHeaders();


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FileGetContentHeadersRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated file_get_content_headers($file) with $file->getDownloadHeaders()',
            [
                new CodeSample(
                    'file_get_content_headers($file);',
                    '$file->getDownloadHeaders();'
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
        if (!($node->name instanceof Name)) {
            return null;
        }

        if ($this->getName($node->name) !== 'file_get_content_headers') {
            return null;
        }

        if (count($node->args) !== 1) {
            return null;
        }

        $fileArg = $node->args[0]->value;

        return new MethodCall($fileArg, 'getDownloadHeaders', []);
    }
}
