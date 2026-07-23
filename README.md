**TL;DR:** [885 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_227 summaries · 116 new this week_

- [#3573022: [upstream] Data loss: `drush config:import` deletes config (e.g. code component...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3573022.md)
- [#3591667: Add `canvas:doctor` Drupal CLI command to check health of Canvas' data:...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591667.md)
- [#3591674: Explicitly support `comment` as a referenceable content entity type (stop...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591674.md)

### Drupal AI

_113 summaries · 15 new this week_

- [#3601381: setup-site: positional config source, multiple named configs in a .drupalaibp/...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601381.md)
- [#3601384: Add an opt-in glab (GitLab CLI) extra: install and authenticate to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601384.md)
- [#3584903: Migrate the inner workings of Surge into AI Best Practices](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3584903.md)

### Drupal Core

_457 summaries · 16 new this week_

- [#3612247: Update guzzlehttp/guzzle to 7.15.1 and guzzlehttp/psr7 to 2.12.3](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3612247.md)
- [#3593123: Add BatchStorageInterface::getId()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3593123.md)
- [#2880374: Experimental modules and themes should not have warnings after being installed](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2880374.md)

### Drupal CMS

_88 summaries · 71 new this week_

- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)
- [#3526844: [meta] Implement the first real-world site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3526844.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_181 rules · 2 new this week_

- [Replace module_set_weight() and module_config_sort() with ModuleWeight service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-module-set-weight-and-module-config-sort-with-3595652.php)
- [Replace Htmx::triggerAfterSettleHeader() and triggerAfterSwapHeader() with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-htmx-triggeraftersettleheader-and-3607711.php)
- [Add missing $imageDerivativeUtilities argument to ImageFormatter constructor...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-missing-imagederivativeutilities-argument-to-3609124.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
