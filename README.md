**TL;DR:** [665 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [177 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_494 summaries · 14 new this week_

- [#3408420: Deleting a workflow can lead to the deletion of unaffected views](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3408420.md)
- [#3599842: guzzlehttp/psr7 needs to be updated to >2.10.2 to fix 2 security issues](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3599842.md)
- [#3585891: Deprecate Validating CSRF tokens with the 'rest' key in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3585891.md)

### Drupal AI

_80 summaries · 2 new this week_

- [#3586397: Switch FieldValidationRule plugins to the Provider Configuration Form Element](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3586397.md)
- [#3595519: Depend on core recipe for image media type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595519.md)
- [#3355087: Support for non-bundle entity types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3355087.md)

### Drupal Canvas

_76 summaries · 13 new this week_

- [#3589076: Detect conflicts for Page entities during the handling of auto-saves/pending...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3589076.md)
- [#3582558: Symmetrically translatable config-defined component trees, STEP 4: allow...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3582558.md)
- [#3591638: Add a computed `src` field property to the image field type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591638.md)

### Drupal CMS

_15 summaries · 3 new this week_

- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)
- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)
- [#3591375: Automatically copy font and color CSS files to Drupal root if a site template...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591375.md)


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
_177 rules · 3 new this week_

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
