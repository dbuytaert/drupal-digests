**TL;DR:** [659 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [176 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal CMS

_15 summaries · 4 new this week_

- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)
- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)
- [#3591375: Automatically copy font and color CSS files to Drupal root if a site template...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591375.md)

### Drupal Canvas

_75 summaries · 12 new this week_

- [#3582558: Symmetrically translatable config-defined component trees, STEP 4: allow...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3582558.md)
- [#3591638: Add a computed `src` field property to the image field type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591638.md)
- [#3574857: Add client-side transform for entity_reference_autocomplete to...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3574857.md)

### Drupal Core

_491 summaries · 15 new this week_

- [#3593963: Field loading can hit the MySQL 61 table join limit if there are ~60 fields](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3593963.md)
- [#3590897: Sidebar toggle is visible and functioning in media library dialog, but...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3590897.md)
- [#3592497: Buttons are not rendered on the bottom of the page for Node create/edit for](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592497.md)

### Drupal AI

_78 summaries · 1 new this week_

- [#3355087: Support for non-bundle entity types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3355087.md)
- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [176 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_176 rules · 3 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace deprecated locale translation status functions with LocaleSource service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-translation-status-functions-with-3590050.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
