**TL;DR:** [645 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [175 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_70 summaries · 8 new this week_

- [#3591633: Move CLI sync settings to canvas.config.json, enable everything by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591633.md)
- [#3591628: On Drupal 11.3, Components are not regenerated when configuration changes (e.g....](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591628.md)
- [#3591624: Block validation is broken for any block without a default value for...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591624.md)

### Drupal Core

_484 summaries · 17 new this week_

- [#3132725: "Limit list to selected items" on exposed filters does not filter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3132725.md)
- [#3594092: loadUnchanged() returns an in-memory-modified entity when hook_entity_preload()...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594092.md)
- [#3592577: Ensure that hook attributes are never parsed from a stale opcache](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3592577.md)

### Drupal CMS

_13 summaries · 2 new this week_

- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)
- [#3591375: Automatically copy font and color CSS files to Drupal root if a site template...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591375.md)
- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)

### Drupal AI

_78 summaries · 1 new this week_

- [#3355087: Support for non-bundle entity types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3355087.md)
- [#3588809: [Sprint 8] Add Phase 5.5 (asset library setup) using canvas:asset_library](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588809.md)
- [#3588808: [Sprint 8] Add Phase 1.5 (content model setup) using content_type and taxonomy...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588808.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [175 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_175 rules · 5 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace user_pass_rehash/user_pass_reset_url/user_cancel_url with...](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-user-pass-rehash-user-pass-reset-url-user-cancel-3581056.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
