**TL;DR:** [1758 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [212 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_667 summaries · 6 new this week_

- [#3641267: Update Twig to v3.31.1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3641267.md)
- [#2448545: Modernize form select option helpers](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2448545.md)
- [#3249027: RoleAccessCheck returns Allowed if user has no role](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3249027.md)

### Drupal Canvas

_353 summaries · 18 new this week_

- [#3592330: Workspaces PR 24: lock a Canvas config draft to one workspace, the same way as...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592330.md)
- [#3592283: Brand Kit – UX Improvements](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592283.md)
- [#3592263: Workspaces PR 15: staged drafts are invisible to the Workspaces API and the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592263.md)

### Drupal CMS

_142 summaries · 5 new this week_

- [#3591537: Add the Cafe site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591537.md)
- [#3586147: Accessibility tools -- switch to Editoria11y 3.x](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3586147.md)
- [#3591532: Drupal CMS installer denies access after site template selection on Drupal core...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591532.md)

### Drupal AI

_596 summaries · 50 new this week_

- [#3594930: Decision recording skips mixed-language questions when a short state value...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594930.md)
- [#3594830: An unnamed calibration fitted from an answer file is listed by its model id](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594830.md)
- [#3594926: Admin text and display fixes: run pass rate label, "binary" scales, Markdown...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594926.md)


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
