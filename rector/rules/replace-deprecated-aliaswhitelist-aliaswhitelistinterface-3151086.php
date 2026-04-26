<?php
declare(strict_types=1);

// Source: https://www.drupal.org/node/3151086
// Drupal Digests (https://github.com/dbuytaert/drupal-digests)
// by Dries Buytaert (https://dri.es)
//
// Drupal 11.1.0 deprecated AliasWhitelist and AliasWhitelistInterface in
// the path_alias module in favour of AliasPrefixList and
// AliasPrefixListInterface, removing exclusionary terminology. The rule
// renames all class references, type hints, new instantiations,
// extends/implements declarations, and calls to the protected
// pathAliasWhitelistRebuild() method. Both symbols are removed in
// drupal:12.0.0.
//
// Before:
//   use Drupal\path_alias\AliasWhitelist;
//   use Drupal\path_alias\AliasWhitelistInterface;
//   
//   class MyList extends AliasWhitelist {}
//   
//   function init(AliasWhitelistInterface $wl): void {
//       $wl = new AliasWhitelist('path_alias_whitelist', $cache, $lock, $state, $repo);
//   }
//   
//   class MyManager extends \Drupal\path_alias\AliasManager {
//       protected function rebuild($path = NULL) {
//           $this->pathAliasWhitelistRebuild($path);
//       }
//   }
//
// After:
//   use Drupal\path_alias\AliasPrefixList;
//   use Drupal\path_alias\AliasPrefixListInterface;
//   
//   class MyList extends \Drupal\path_alias\AliasPrefixList {}
//   
//   function init(\Drupal\path_alias\AliasPrefixListInterface $wl): void {
//       $wl = new \Drupal\path_alias\AliasPrefixList('path_alias_prefix_list', $cache, $lock, $state, $repo);
//   }
//   
//   class MyManager extends \Drupal\path_alias\AliasManager {
//       protected function rebuild($path = NULL) {
//           $this->pathAliasPrefixListRebuild($path);
//       }
//   }


use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use Rector\Config\RectorConfig;
use Rector\Rector\AbstractRector;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Renames the deprecated AliasManager::pathAliasWhitelistRebuild() protected
 * method call to pathAliasPrefixListRebuild(), deprecated in drupal:11.1.0
 * and removed in drupal:12.0.0.
 */
final class RenamePathAliasWhitelistRebuildRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename deprecated AliasManager::pathAliasWhitelistRebuild() to pathAliasPrefixListRebuild()',
            [
                new CodeSample(
                    '$this->pathAliasWhitelistRebuild($path);',
                    '$this->pathAliasPrefixListRebuild($path);'
                ),
            ]
        );
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /** @param MethodCall $node */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isName($node->name, 'pathAliasWhitelistRebuild')) {
            return null;
        }
        $node->name = new Identifier('pathAliasPrefixListRebuild');
        return $node;
    }
}
