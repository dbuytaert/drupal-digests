**TL;DR:** [1377 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [211 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal AI

_308 summaries · 29 new this week_

- [#3585954: Replace SessionLockingStreamableHttpTransport with the SDK's session lock](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585954.md)
- [#3585949: Allow mcp/sdk ^0.9](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585949.md)
- [#3585946: Concurrent tool calls on one session can receive each other's elicitation...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/mcp-server/3585946.md)

### Drupal Canvas

_298 summaries · 11 new this week_

- [#3592257: Support non-revisionable resources in Headless SDK draft mode](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592257.md)
- [#3592224: Allow component definition updates across headless frontends](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592224.md)
- [#3592221: Canvas AI: Show the selected component in the AI chat input](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592221.md)

### Drupal Core

_638 summaries · 8 new this week_

- [#2980380: Inline error messages are not using proper theming mechanisms](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2980380.md)
- [#2350049: filter_autop() returns <br /> but should return <br> for HTML5](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2350049.md)
- [#2735997: Decimal separator and precision settings unavailable when aggregating decimal...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/2735997.md)

### Drupal CMS

_133 summaries · 4 new this week_

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
