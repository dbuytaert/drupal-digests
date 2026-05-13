**TL;DR:** [567 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [166 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_435 summaries · 66 new this week_

- [#3301239: Improve the error message thrown in PoStreamReader::readLine() when...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3301239.md)
- [#3587298: RouteProcessorCsrf::processOutbound() should bubble 'session' cache context for...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3587298.md)
- [#2844620: Automatically split cache debug headers into multiple lines when they exceed 8k](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2844620.md)

### Drupal Canvas

_58 summaries · 21 new this week_

- [#3589128: Automate review using PHPStan: adopt shipmonk-rnd/dead-code-detector](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3589128.md)
- [#3588661: Make CLI builds use shared Vite infrastructure](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3588661.md)
- [#3581110: Add Multi-Value List Text/Integer Prop Support (UI)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3581110.md)

### Drupal AI

_63 summaries · 6 new this week_

- [#3588596: Add Webhook support, Project support and events for this](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588596.md)
- [#3577857: Allow Document Loader to define LLM inputs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3577857.md)
- [#3580850: Integrate with the MDX editor](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3580850.md)

### Drupal CMS

_11 summaries · 3 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [166 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_166 rules · 23 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace NodeViewController with EntityViewController](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-nodeviewcontroller-with-entityviewcontroller-3589630.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
