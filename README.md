**TL;DR:** [592 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [169 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_76 summaries · 18 new this week_

- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)
- [#3588799: [Sprint 8] Canvas content templates as a phase for content-type migrations](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588799.md)

### Drupal Core

_444 summaries · 58 new this week_

- [#3590425: Remove usage of property hook for DrupalTestCaseTrait::$root ](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3590425.md)
- [#1452100: Private file download returns access denied, when file attached to revision...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/1452100.md)
- [#3590237: Add PHP 8.6 polyfill](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3590237.md)

### Drupal Canvas

_61 summaries · 24 new this week_

- [#3585327: Add HTTP API endpoint for canvasData.v0 to support useSiteData() in Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585327.md)
- [#3587374: Add HostEntityPropSource so content-entity-reference props can resolve to the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587374.md)
- [#3586613: Add content-entity-reference well-known prop shape for code components](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3586613.md)

### Drupal CMS

_11 summaries · 3 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [169 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_169 rules · 8 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace drupal_static_reset() file_get_file_references keys with cache tag...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-drupal-static-reset-file-get-file-references-keys-1452100.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
