**TL;DR:** [1368 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Canvas

_294 summaries · 8 new this week_

- [#3592123: Canvas AI: removeMediaFields() drops arrays of media IDs for multi-value media...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592123.md)
- [#3592176: Duplicating a page template silently does nothing: the GET representation is no...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592176.md)
- [#3592122: Canvas AI: get_component_details and get_component_context omit item type and...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592122.md)

### Drupal Core

_636 summaries · 6 new this week_

- [#2735997: Decimal separator and precision settings unavailable when aggregating decimal...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2735997.md)
- [#3082239: Forbid limited length primary and unique keys, allow only in indexes](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3082239.md)
- [#3594338: Add a command to import configuration](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3594338.md)

### Drupal AI

_305 summaries · 28 new this week_

- [#3586702: Emit automator config ID tag and entity_context on chat requests](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586702.md)
- [#3594806: Show the input and the model's answer when reviewing recorded decisions](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594806.md)
- [#3594822: Failure mode rates count each reviewer's annotation of the same question](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai-eval/3594822.md)

### Drupal CMS

_133 summaries · 5 new this week_

- [#3591507: Add a hard opt-out for telemetry via settings.php](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591507.md)
- [#3591501: Add an event or param to track use of the launcher](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591501.md)
- [#3591510: Switch `drush si` default site template to Forma](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591510.md)


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
_211 rules · 2 new this week_

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
