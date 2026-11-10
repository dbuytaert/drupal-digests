**TL;DR:** [1575 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal CMS

_135 summaries · 2 new this week_

- [#3591523: Add the Ágora Transparency site template to the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591523.md)
- [#3591521: Remove the hardcoded LLM configuration since it will not work with Amazee](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591521.md)
- [#3591507: Add a hard opt-out for telemetry via settings.php](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591507.md)

### Drupal Core

_650 summaries · 12 new this week_

- [#3357616: Remove "Forgot your password?" link on failed login attempt message if user has...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3357616.md)
- [#3578936: Navigation module and Admin theme should use the same Inter font](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3578936.md)
- [#3181762: The field name is not displayed in LinkWidget error messages](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3181762.md)

### Drupal AI

_477 summaries · 56 new this week_

- [#3583114: Nullable inputs use two spellings in the generated JSON Schema](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583114.md)
- [#3583070: Config sequences keyed by name are derived as lists, so their schema says array...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583070.md)
- [#3582977: Pass entities between drush tool:run calls as handles](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3582977.md)

### Drupal Canvas

_313 summaries · 11 new this week_

- [#3592237: Workspaces PR 7a: the workspace is the unit of publish (back end)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592237.md)
- [#3592256: Add `dr canvas:audit:usage` command](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592256.md)
- [#3592222: Canvas AI: Stopped turn's message is lost when tool calls are kept in history](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592222.md)


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
