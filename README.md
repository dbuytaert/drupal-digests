**TL;DR:** [631 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [174 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_78 summaries · 1 new this week_

- [#3355087: Support for non-bundle entity types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3355087.md)
- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)

### Drupal Core

_478 summaries · 20 new this week_

- [#3591076: JS translation files should be generated and served from assets://](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3591076.md)
- [#3041170: RowPluginBase::render() update docblock and trigger deprecation for old typehint](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3041170.md)
- [#3593202: Drop the explicit clear of plugin caches in drupal_flush_all_caches()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3593202.md)

### Drupal Canvas

_63 summaries · 1 new this week_

- [#3590572: Indicate translation availability per language in the Canvas language switcher...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3590572.md)
- [#3585327: Add HTTP API endpoint for canvasData.v0 to support useSiteData() in Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585327.md)
- [#3587374: Add HostEntityPropSource so content-entity-reference props can resolve to the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587374.md)

### Drupal CMS

_12 summaries · 1 new this week_

- [#3591375: Automatically copy font and color CSS files to Drupal root if a site template...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591375.md)
- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [174 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_174 rules · 4 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Add ResultRow type hint to RowPluginBase::render() overrides](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-resultrow-type-hint-to-rowpluginbase-render-overrides-3041170.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
