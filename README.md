**TL;DR:** [630 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [173 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_478 summaries · 22 new this week_

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

### Drupal AI

_77 summaries · 0 new this week_

- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)
- [#3588799: [Sprint 8] Canvas content templates as a phase for content-type migrations](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588799.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [173 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_173 rules · 3 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Add $yamlCacheCollector arg to LibraryDiscoveryParser and YamlRouteDiscovery...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/add-yamlcachecollector-arg-to-librarydiscoveryparser-and-3486503.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
