<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3534092
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Replaces calls to the deprecated file_system_settings_submit()
// procedural function with the equivalent static method
// \Drupal\file\Hook\FileHooks::settingsSubmit(). The function was
// deprecated in drupal:11.3.0 and will be removed in drupal:12.0.0.
// Automating this rewrite prevents runtime deprecation warnings in
// modules that invoke the form submit handler directly.
//
// Before:
//   file_system_settings_submit($form, $form_state);
//
// After:
//   \Drupal\file\Hook\FileHooks::settingsSubmit($form, $form_state);


use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class FileSystemSettingsSubmitRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace deprecated file_system_settings_submit() function calls with \\Drupal\\file\\Hook\\FileHooks::settingsSubmit()',
            [
                new CodeSample(
                    'file_system_settings_submit($form, $form_state);',
                    '\\Drupal\\file\\Hook\\FileHooks::settingsSubmit($form, $form_state);'
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
        if (!$this->isName($node->name, 'file_system_settings_submit')) {
            return null;
        }

        return new StaticCall(
            new FullyQualified('Drupal\\file\\Hook\\FileHooks'),
            new Identifier('settingsSubmit'),
            $node->args
        );
    }
}
