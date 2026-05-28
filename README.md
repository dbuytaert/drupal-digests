**TL;DR:** [606 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [170 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_456 summaries · 8 new this week_

- [#3592341: Fix dot-segment encoding for chained "../" and "./" in generated URLs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592341.md)
- [#3592421: Update Symfony routing and http-foundation versions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592421.md)
- [#3592065: Drupal core-recommended pins vulnerable symfony/polyfill-intl-idn version...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592065.md)

### Drupal AI

_77 summaries · 0 new this week_

- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)
- [#3588799: [Sprint 8] Canvas content templates as a phase for content-type migrations](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588799.md)

### Drupal Canvas

_62 summaries · 0 new this week_

- [#3585327: Add HTTP API endpoint for canvasData.v0 to support useSiteData() in Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585327.md)
- [#3587374: Add HostEntityPropSource so content-entity-reference props can resolve to the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587374.md)
- [#3576863: Manage Content Templates within Canvas Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3576863.md)

### Drupal CMS

_11 summaries · 0 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [170 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_170 rules · 1 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace locale_translation_update_file_history() and...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-locale-translation-update-file-history-and-locale-3037156.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
