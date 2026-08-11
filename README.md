**TL;DR:** [937 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [187 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_236 summaries · 4 new this week_

- [#3569120: Canvas AI: Expose Props of Blocks to the Agent](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3569120.md)
- [#3591785: Strengthen AutoSaveManager to generate stable hashes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591785.md)
- [#3591905: `drush config:import` can fail to update a Component whose `active_version`...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591905.md)

### Drupal Core

_491 summaries · 9 new this week_

- [#2171397: Deprecate remaining options.module functions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2171397.md)
- [#3517430: Add an attribute for skipping PHPUnit tests](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3517430.md)
- [#3587565: Static cache entities that aren't loaded](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3587565.md)

### Drupal CMS

_90 summaries · 1 new this week_

- [#3489408: Enable filenames sanitization](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3489408.md)
- [#3591420: Add Summit site template to site-templates.yml](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591420.md)
- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)

### Drupal AI

_120 summaries · 2 new this week_

- [#3588977: Behavioral eval runner: the default (claude) provider runs the agent inside the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588977.md)
- [#3553475: Deletion of chunks vectors fails upon saving a node after editing](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3553475.md)
- [#3612042: Enable RateLimits](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3612042.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [187 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_187 rules · 2 new this week_

- [Replace options_allowed_values() with the OptionsAllowedValuesInterface service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-options-allowed-values-with-the-2171397.php)
- [Add $yaml_cache_collector argument to Local Action/Task/MenuLink manager...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-yaml-cache-collector-argument-to-local-action-task-3593485.php)
- [Promote defaults._title to top-level title on #[Route] attributes](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/promote-defaults-title-to-top-level-title-on-route-3607968.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
