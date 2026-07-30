**TL;DR:** [909 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [184 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_473 summaries · 16 new this week_

- [#3584238: Deprecate implicit commit-on-destruct](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3584238.md)
- [#3050199: Remove entity level constraint MenuSettingsConstraint that validates property...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3050199.md)
- [#3209204: Adding existing translation throws "Invalid translation language specified"](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3209204.md)

### Drupal Canvas

_232 summaries · 5 new this week_

- [#3591656: Support multi-target-bundle references in code component...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591656.md)
- [#3582200: component_tree sequence keys sorted lexicographically instead of numerically,...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3582200.md)
- [#3591820: Publishing a symmetrically-translated canvas_page 422s when a default-language...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591820.md)

### Drupal CMS

_89 summaries · 1 new this week_

- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)

### Drupal AI

_115 summaries · 2 new this week_

- [#3601386: Support multiple configs in one repo: order the picker by a weight key, falling...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601386.md)
- [#3613451: Make it possible to select agent supplied/default model](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3613451.md)
- [#3601381: setup-site: positional config source, multiple named configs in a .drupalaibp/...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601381.md)


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
