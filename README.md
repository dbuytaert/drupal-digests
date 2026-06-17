**TL;DR:** [674 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [177 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_81 summaries · 7 new this week_

- [#3590948: Add TMGMT translation integration for content entities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3590948.md)
- [#3591684: UI Exception / 500 Error when triggering Preview on Content Templates causes...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591684.md)
- [#3591584: Publishing a canvas_page via auto-save API drops all non-default-language...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591584.md)

### Drupal CMS

_16 summaries · 2 new this week_

- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)
- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)

### Drupal Core

_497 summaries · 7 new this week_

- [#3590350: WorkspacePublisher doesn't roll back when a PHP Error is thrown during...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3590350.md)
- [#3524377: Allow to skip OOP hooks and services for modules that are not installed](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3524377.md)
- [#3400181: [regression] calling TypedConfigManager::getDefinition() causes cache pollution](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3400181.md)

### Drupal AI

_80 summaries · 2 new this week_

- [#3586397: Switch FieldValidationRule plugins to the Provider Configuration Form Element](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3586397.md)
- [#3595519: Depend on core recipe for image media type](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595519.md)
- [#3355087: Support for non-bundle entity types](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3355087.md)


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
