**TL;DR:** [1609 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_322 summaries · 16 new this week_

- [#3592331: Update the preview in place on prop edits instead of reloading the iframe](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592331.md)
- [#3592337: Render headless previews on unpublished canonical entity pages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592337.md)
- [#3592239: Workspaces PR 8: workspace management API and switcher UI](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592239.md)

### Drupal AI

_497 summaries · 54 new this week_

- [#3586417: Apply unified UX/design and Provider Config element to Audio-To-Audio Explorer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586417.md)
- [#3586409: Apply unified UX/design and Provider Config element to Moderation Explorer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586409.md)
- [#3586410: Apply unified UX/design and Provider Config element to Object Detection Explorer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586410.md)

### Drupal Core

_655 summaries · 12 new this week_

- [#3623380: Create OOP replacement for hook_install_tasks and hook_install_tasks_alter](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3623380.md)
- [#3617943: Deprecate DateTimePlus::prepareFormat()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3617943.md)
- [#3621330: Include Bandwidth optimization (aggregation) on Status report page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3621330.md)

### Drupal CMS

_135 summaries · 1 new this week_

- [#3591523: Add the Ágora Transparency site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591523.md)
- [#3591521: Remove the hardcoded LLM configuration since it will not work with Amazee](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591521.md)
- [#3591507: Add a hard opt-out for telemetry via settings.php](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591507.md)


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
