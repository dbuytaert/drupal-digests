**TL;DR:** [1776 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [212 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_671 summaries · 9 new this week_

- [#3641618: Update Symfony](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3641618.md)
- [#3622582: Cleanup database exceptions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3622582.md)
- [#3328425: CKEditor 5 balloons invisible when CKEditor 5 is used inside a modal](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3328425.md)

### Drupal CMS

_143 summaries · 4 new this week_

- [#3591538: Add the GovStarter site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591538.md)
- [#3591537: Add the Cafe site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591537.md)
- [#3586147: Accessibility tools -- switch to Editoria11y 3.x](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3586147.md)

### Drupal Canvas

_357 summaries · 12 new this week_

- [#3592305: Workspaces PR 20: log the 1.x drafts that the migration drops](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592305.md)
- [#3592369: Add a CLI command to manage headless frontend URLs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592369.md)
- [#3592306: Workspaces PR 23: a queued layout write goes to the page the editor shows when...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592306.md)

### Drupal AI

_605 summaries · 45 new this week_

- [#3586913: Tools Explorer shows no argument fields for Tool API tools](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586913.md)
- [#3594928: The run box: Cancel does nothing while a progress request is open, and a run...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594928.md)
- [#3594927: Imported results: the results list draws the score on the wrong scale, and the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594927.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [212 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_212 rules · 1 new this week_

- [Replace form_select_options() with FormSelectOptions::process()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-form-select-options-with-formselectoptions-process-2448545.php)
- [Replace Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php)
- [Rename hook_system_info_alter implementations to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-system-info-alter-implementations-to-hook-3623862.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
