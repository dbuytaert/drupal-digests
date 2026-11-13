**TL;DR:** [1622 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_325 summaries · 19 new this week_

- [#3592340: Show changes to the layout in the preview immediately, and keep stale server...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592340.md)
- [#3592327: A code component cannot get the URL of a referenced entity, so a list of...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592327.md)
- [#3592231: Workspaces PR 9: Stage a new page as published so one workspace publish makes...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592231.md)

### Drupal AI

_505 summaries · 54 new this week_

- [#3586412: Apply unified UX/design and Provider Config element to Text-To-Image Generation...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586412.md)
- [#3586406: Apply unified UX/design and Provider Config element to Summarize Text Explorer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586406.md)
- [#3586408: Apply unified UX/design and Provider Config element to Rerank Explorer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586408.md)

### Drupal CMS

_136 summaries · 2 new this week_

- [#3591527: 🤖 drupal_cms_site_template_base leaves two Navigation components enabled in...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591527.md)
- [#3591523: Add the Ágora Transparency site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591523.md)
- [#3591521: Remove the hardcoded LLM configuration since it will not work with Amazee](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591521.md)

### Drupal Core

_656 summaries · 13 new this week_

- [#3634655: Update to Symfony 8.2.0-BETA1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3634655.md)
- [#3623380: Create OOP replacement for hook_install_tasks and hook_install_tasks_alter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3623380.md)
- [#3617943: Deprecate DateTimePlus::prepareFormat()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3617943.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_211 rules · 0 new this week_

- [Replace Schema::ensureNotNullPrimaryKey() with validatePrimaryKeySchema()](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-schema-ensurenotnullprimarykey-with-3082239.php)
- [Rename hook_system_info_alter implementations to hook_extension_info_alter](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/rename-hook-system-info-alter-implementations-to-hook-3623862.php)
- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
