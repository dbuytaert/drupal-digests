**TL;DR:** [582 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [168 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_61 summaries · 24 new this week_

- [#3585327: Add HTTP API endpoint for canvasData.v0 to support useSiteData() in Workbench](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3585327.md)
- [#3587374: Add HostEntityPropSource so content-entity-reference props can resolve to the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3587374.md)
- [#3586613: Add content-entity-reference well-known prop shape for code components](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3586613.md)

### Drupal Core

_441 summaries · 58 new this week_

- [#3516173: Block status code visibility condition should use a status code cache context](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3516173.md)
- [#3581303: Convert locale batch callbacks](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3581303.md)
- [#3579253: run-tests.sh does not properly process PHPUnit output when no test are executed...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3579253.md)

### Drupal AI

_69 summaries · 11 new this week_

- [#3588588: Add Gemini CLI backend for harness-native plugin install](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588588.md)
- [#3588587: Add Codex backend for harness-native plugin install](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588587.md)
- [#3588596: Add Webhook support, Project support and events for this](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-ai/3588596.md)

### Drupal CMS

_11 summaries · 3 new this week_

- [#3580694: The project template should always place config outside the web root by default](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3580694.md)
- [#3579163: Add support for listing paid site templates in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579163.md)
- [#3579729: Add an `overwrite` option to `drush site:export`](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3579729.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [168 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_168 rules · 7 new this week_

- [Replace views_entity_field_label() with EntityFieldManager::getFieldLabels()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-views-entity-field-label-with-entityfieldmanager-3069442.php)
- [Remove deprecated $long parameter from FilterInterface::tips()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-long-parameter-from-filterinterface-tips-3505370.php)
- [Replace deprecated locale batch procedural functions with service method calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-locale-batch-procedural-functions-with-3581303.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
