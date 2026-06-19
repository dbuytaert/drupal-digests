**TL;DR:** [688 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [177 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_501 summaries · 10 new this week_

- [#3593233: Cloning an aggregate entity query shares its aggregate conditions with the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3593233.md)
- [#3585723: #date_year_range does not support years < 1000](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3585723.md)
- [#3597406: [regression] DrupalApplication (for `dr`) needs to support DRUPAL_DEV_SITE_PATH...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3597406.md)

### Drupal Canvas

_90 summaries · 14 new this week_

- [#3587587: [META] Review of changes and Conflict resolution](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587587.md)
- [#3591699: Entity-reference component inputs (e.g. image src) are exposed as translatable,...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591699.md)
- [#3591678: Coalesce reference fields whose values are consumed only through nested objects](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591678.md)

### Drupal AI

_81 summaries · 1 new this week_

- [#3604032: Add static_list completion providers to example prompt argument configs and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3604032.md)
- [#3586397: Switch FieldValidationRule plugins to the Provider Configuration Form Element](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3586397.md)
- [#3595519: Depend on core recipe for image media type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595519.md)

### Drupal CMS

_16 summaries · 1 new this week_

- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)
- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [177 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_177 rules · 1 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace CsrfTokenGenerator::get('rest') with TOKEN_KEY constant](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-csrftokengenerator-get-rest-with-token-key-constant-3585891.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
