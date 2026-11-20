**TL;DR:** [1711 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_564 summaries · 59 new this week_

- [#3586880: Add Import JSON and Export JSON to the text-only AI API Explorers](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586880.md)
- [#3586796: Drupal 12 compatibility: add ^12 to core_version_requirement and fix...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586796.md)
- [#3586749: Consumer settings form and post-update 0008 fail with a database error when a...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-context/3586749.md)

### Drupal CMS

_139 summaries · 3 new this week_

- [#3591533: setComponentTree fails when a second module with components is installed in the...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591533.md)
- [#3591530: Add the Saplings site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591530.md)
- [#3591524: Search is 404 for non top-level pages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591524.md)

### Drupal Canvas

_346 summaries · 21 new this week_

- [#3592391: Workspaces PR 28: in a workspace the user can view, the Canvas API refuses...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592391.md)
- [#3592390: canvas_oauth should automatically create the CLI user, and consumer, upon...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592390.md)
- [#3592387: Workspaces PR 27: list, activate and delete only default-provider workspaces...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592387.md)

### Drupal Core

_662 summaries · 6 new this week_

- [#3639248: Remove deprecated class properties](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3639248.md)
- [#3638074: Update to Symfony 8.2.0-BETA2](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3638074.md)
- [#3634418: Implement better messaging for Assert for css in libraries discovery](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3634418.md)


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
