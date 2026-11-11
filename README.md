**TL;DR:** [1590 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_488 summaries · 57 new this week_

- [#3586778: Shared AI API Explorer input picker: recent results, media library or upload](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586778.md)
- [#3586775: Redesign the AI API Explorers list as a hub that survives a broken provider](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586775.md)
- [#3586776: Shared audio player and image viewers for AI API Explorer results](https://github.com/dbuytaert/drupal-digests/blob/main/issues/ai/3586776.md)

### Drupal Canvas

_315 summaries · 10 new this week_

- [#3592325: Mutations keep failing with a stale CSRF token when the 403 response has an...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592325.md)
- [#3592238: Workspaces PR 7b: the workspace is the unit of publish (UI)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592238.md)
- [#3592237: Workspaces PR 7a: the workspace is the unit of publish (back end)](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592237.md)

### Drupal Core

_652 summaries · 10 new this week_

- [#3273334: Unable to unselect a multi select exposed filter in ajax view](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3273334.md)
- [#3635924: Upgrade htmx to 4.0.1](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3635924.md)
- [#3357616: Remove "Forgot your password?" link on failed login attempt message if user has...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3357616.md)

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
