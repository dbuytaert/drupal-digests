**TL;DR:** [947 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [181 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_567 summaries · 16 new this week_

- [#3586221: Remove the Stable 9 theme](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3586221.md)
- [#3605549: Allow install profiles to specify a list of recipes that should be applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3605549.md)
- [#3101714: Link field display should show plaintext URLs longer than 80 chars](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3101714.md)

### Drupal CMS

_88 summaries · 71 new this week_

- [#3577804: The installer should set a state flag to remember what site template was applied](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3577804.md)
- [#3542339: Make the blank site option usable](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3542339.md)
- [#3526844: [meta] Implement the first real-world site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3526844.md)

### Drupal Canvas

_187 summaries · 76 new this week_

- [#3591130: Deleted (code) components can still be referenced by folders: dependency...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591130.md)
- [#3576837: Manage the global layout (aka regions) within Canvas Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3576837.md)
- [#3556101: [11.3.x] Semi-coupled engine not compatible with Drupal 11.x](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3556101.md)

### Drupal AI

_105 summaries · 9 new this week_

- [#3601380: Separate composer and recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601380.md)
- [#3601379: Config-url clobbers the scaffold composer.json](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3601379.md)
- [#3609590: Switch to official MCP PHP SDK Client](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3609590.md)


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
