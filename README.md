**TL;DR:** [1597 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_654 summaries · 11 new this week_

- [#3617943: Deprecate DateTimePlus::prepareFormat()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3617943.md)
- [#3621330: Include Bandwidth optimization (aggregation) on Status report page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3621330.md)
- [#3273334: Unable to unselect a multi select exposed filter in ajax view](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3273334.md)

### Drupal AI

_491 summaries · 48 new this week_

- [#3601418: --yolo still prompts for a project name when a config source is given](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601418.md)
- [#3601417: DDEV's usage-statistics prompt blocks unattended (--yolo) installs](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601417.md)
- [#3601416: Add certutil on Linux](https://github.com/dbuytaert/drupal-digests/blob/main/issues/one-line-installer/3601416.md)

### Drupal Canvas

_317 summaries · 11 new this week_

- [#3592239: Workspaces PR 8: workspace management API and switcher UI](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592239.md)
- [#3592316: A `type: array, items: {type: object …}` prop linked to a multi-valued entity...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592316.md)
- [#3592325: Mutations keep failing with a stale CSRF token when the 403 response has an...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592325.md)

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
