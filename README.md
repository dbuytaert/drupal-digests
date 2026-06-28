**TL;DR:** [709 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [178 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_514 summaries · 12 new this week_

- [#3585894: LLM harm reduction in Drupal core contribution, AGENTS.md guidelines](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3585894.md)
- [#3587758: [PP-1] Labels for accent color choices on settings form are hidden from...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3587758.md)
- [#3072557: Plugin ID menu_link_content was not found in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3072557.md)

### Drupal AI

_83 summaries · 2 new this week_

- [#3593019: Add ImageToImage possibilities](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3593019.md)
- [#3590873: Dall-E 3 has been deprecated and should be removed as default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3590873.md)
- [#3604032: Add static_list completion providers to example prompt argument configs and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3604032.md)

### Drupal Canvas

_96 summaries · 5 new this week_

- [#3591704: Only show default-language entities when reviewing auto-saved (pending) changes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591704.md)
- [#3585970: UI changes for detection of conflicts caused by external updates to underlying...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585970.md)
- [#3591702: Fatal error (AssertionError on dev env) when a component instance's image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591702.md)

### Drupal CMS

_16 summaries · 0 new this week_

- [#3483394: Build privacy advanced recipe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3483394.md)
- [#3583960: Replace friendlycaptcha with ALTCHA](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3583960.md)
- [#3499319: Reponsive images should use sizes attribute for container-width sensitive image...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3499319.md)


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
_178 rules · 1 new this week_

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
