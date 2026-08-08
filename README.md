**TL;DR:** [931 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [186 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_488 summaries · 13 new this week_

- [#3614153: Install system module alongside other modules in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3614153.md)
- [#3593485: Use YamlCacheCollectorDiscovery for links, tasks, and actions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3593485.md)
- [#1945262: Introduce "before" and "after" for conditional ordering in library definitions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/1945262.md)

### Drupal Canvas

_235 summaries · 3 new this week_

- [#3591785: Strengthen AutoSaveManager to generate stable hashes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591785.md)
- [#3591905: `drush config:import` can fail to update a Component whose `active_version`...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591905.md)
- [#3591851: Allow patterns to be edited](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591851.md)

### Drupal AI

_119 summaries · 1 new this week_

- [#3553475: Deletion of chunks vectors fails upon saving a node after editing](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3553475.md)
- [#3612042: Enable RateLimits](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3612042.md)
- [#3568659: Support batched embeddings](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3568659.md)

### Drupal CMS

_89 summaries · 0 new this week_

- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [186 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_186 rules · 2 new this week_

- [Add $yaml_cache_collector argument to Local Action/Task/MenuLink manager...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-yaml-cache-collector-argument-to-local-action-task-3593485.php)
- [Promote defaults._title to top-level title on #[Route] attributes](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/promote-defaults-title-to-top-level-title-on-route-3607968.php)
- [Replace FileStorageFactory::getSync() with the config.storage.sync service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-filestoragefactory-getsync-with-the-config-storage-2951046.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
