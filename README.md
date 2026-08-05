**TL;DR:** [925 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [185 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_234 summaries · 3 new this week_

- [#3591905: `drush config:import` can fail to update a Component whose `active_version`...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591905.md)
- [#3591851: Allow patterns to be edited](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591851.md)
- [#3591656: Support multi-target-bundle references in code component...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591656.md)

### Drupal Core

_484 summaries · 16 new this week_

- [#3604286: Upsert - Allow to customize the behavior of the update when the insert fails...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3604286.md)
- [#3565258: Support library-specific aggregates](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3565258.md)
- [#3524971: Defend against \GuzzleHttp\Cookie\FileCookieJar gadget chain](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3524971.md)

### Drupal AI

_118 summaries · 3 new this week_

- [#3612042: Enable RateLimits](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3612042.md)
- [#3568659: Support batched embeddings](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3568659.md)
- [#3613982: Bug with GPT 5.6 Luna](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3613982.md)

### Drupal CMS

_89 summaries · 0 new this week_

- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [185 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_185 rules · 1 new this week_

- [Promote defaults._title to top-level title on #[Route] attributes](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/promote-defaults-title-to-top-level-title-on-route-3607968.php)
- [Replace FileStorageFactory::getSync() with the config.storage.sync service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-filestoragefactory-getsync-with-the-config-storage-2951046.php)
- [Rename UserSearch to SearchUser from search_user module](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-usersearch-to-searchuser-from-search-user-module-3588379.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
