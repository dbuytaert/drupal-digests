**TL;DR:** [723 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [178 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_98 summaries · 4 new this week_

- [#3587526: Prevention of publishing on Conflict in Canvas UI](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587526.md)
- [#3584136: Canvas AI - fix deprecated Image import, unsupported package handling,...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3584136.md)
- [#3591704: Only show default-language entities when reviewing auto-saved (pending) changes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591704.md)

### Drupal Core

_522 summaries · 9 new this week_

- [#3605551: Allow install profiles to be automatically uninstalled](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3605551.md)
- [#3569137: Ensure TRUNCATE ::execute() method returns void](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3569137.md)
- [#3584793: Use PHP attributes for form route discovery](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3584793.md)

### Drupal AI

_86 summaries · 4 new this week_

- [#3600886: Add an "AI audio" field validation constraint (speech-to-text)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3600886.md)
- [#3595515: Add AI Text Classification field validation rule (mirror of AI Image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3595515.md)
- [#3600885: Add an "AI moderation" field validation constraint](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3600885.md)

### Drupal CMS

_17 summaries · 1 new this week_

- [#3591409: Unable to add components to any Canvas pages in any site template](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591409.md)
- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [178 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_178 rules · 0 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace deprecated locale submit callbacks with service method calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-submit-callbacks-with-service-3595084.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
