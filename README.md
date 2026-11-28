**TL;DR:** [1780 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [213 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_607 summaries · 39 new this week_

- [#3586783: Uninstalling CCC deletes roles that have the permission to translate context...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586783.md)
- [#3586038: Automated Drupal 12 compatibility fixes for ai_agents 1.4.x-dev](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-agents/3586038.md)
- [#3586913: Tools Explorer shows no argument fields for Tool API tools](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586913.md)

### Drupal Core

_672 summaries · 10 new this week_

- [#3123401: Stop caching route lookups with query parameters or without results](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3123401.md)
- [#3641618: Update Symfony](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3641618.md)
- [#3622582: Cleanup database exceptions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3622582.md)

### Drupal Canvas

_358 summaries · 12 new this week_

- [#3592354: Canvas AI: Remove the run counter from the Canvas page agent's system prompt](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592354.md)
- [#3592305: Workspaces PR 20: log the 1.x drafts that the migration drops](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592305.md)
- [#3592369: Add a CLI command to manage headless frontend URLs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592369.md)

### Drupal CMS

_143 summaries · 3 new this week_

- [#3591538: Add the GovStarter site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591538.md)
- [#3591537: Add the Cafe site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591537.md)
- [#3586147: Accessibility tools -- switch to Editoria11y 3.x](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3586147.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [213 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_213 rules · 2 new this week_

- [Reset TypedDataManager::$prototypes with a WeakMap instead of an array](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/reset-typeddatamanager-prototypes-with-a-weakmap-instead-of-3616328.php)
- [Replace form_select_options() with FormSelectOptions::process()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-form-select-options-with-formselectoptions-process-2448545.php)
- [Replace Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
