**TL;DR:** [896 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [184 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_114 summaries · 9 new this week_

- [#3613451: Make it possible to select agent supplied/default model](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3613451.md)
- [#3601381: setup-site: positional config source, multiple named configs in a .drupalaibp/...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601381.md)
- [#3601384: Add an opt-in glab (GitLab CLI) extra: install and authenticate to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601384.md)

### Drupal Core

_465 summaries · 16 new this week_

- [#2951046: Allow parsing and writing PHP class constants and enums in YAML files](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2951046.md)
- [#3588379: Move search from user module to Search](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3588379.md)
- [#3579778: Removed modules should be included as a replace in composer.json](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3579778.md)

### Drupal Canvas

_229 summaries · 117 new this week_

- [#3591857: Removing a media item from one image prop deletes the stored value of every...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591857.md)
- [#3591716: Boolean props auto-enable when another prop is changed in the Canvas form](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591716.md)
- [#3573022: [upstream] Data loss: `drush config:import` deletes config (e.g. code component...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3573022.md)

### Drupal CMS

_88 summaries · 71 new this week_

- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)
- [#3526844: [meta] Implement the first real-world site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3526844.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [184 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_184 rules · 3 new this week_

- [Replace FileStorageFactory::getSync() with the config.storage.sync service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-filestoragefactory-getsync-with-the-config-storage-2951046.php)
- [Rename UserSearch to SearchUser from search_user module](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-usersearch-to-searchuser-from-search-user-module-3588379.php)
- [Replace StubPDO::class with \PDO::class in tests](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-stubpdo-class-with-pdo-class-in-tests-3585476.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
