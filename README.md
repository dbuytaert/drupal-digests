**TL;DR:** [1208 summaries](https://github.com/dbuytaert/drupal-digests/blob/main/issues) of notable Drupal changes and [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules) to help you upgrade. Stay up to date about new additions using the [RSS feeds](#rss-feeds) below.

## Recent changes

AI-generated summaries of [notable Drupal commits](https://github.com/dbuytaert/drupal-digests/blob/main/issues), filtered by impact and community interest.

### Drupal Core

_616 summaries · 10 new this week_

- [#3575642: Always free up old container on rebuild, not only in the installer](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3575642.md)
- [#3625895: Deprecate and remove Query::__wakeup() and ::__sleep()](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/3625895.md)
- [#1411074: Allow kernel tests to share the test environment](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-core/1411074.md)

### Drupal AI

_197 summaries · 13 new this week_

- [#3583049: Deprecate `multiple` on input and output definitions in favor of list...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583049.md)
- [#3583078: Decide before rc1: separate invocation state from the tool definition behind...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583078.md)
- [#3583085: Tell callers which input a failed tool call is about](https://github.com/dbuytaert/drupal-digests/blob/main/issues/tool-api/3583085.md)

### Drupal Canvas

_279 summaries · 9 new this week_

- [#3591894: Error message for empty value is displayed for select props even when they're...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591894.md)
- [#3591848: ∅ is added for empty fields, forcing translation and rendering on the page](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3591848.md)
- [#3592084: Canvas entity edit/delete/translation routes render in the front-end theme...](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-canvas/3592084.md)

### Drupal CMS

_116 summaries · 12 new this week_

- [#3591490: Add an initial 'ping' when opting into telemetry](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591490.md)
- [#3591489: Use the Tagify User List widget by default for user reference fields](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591489.md)
- [#3591488: Add telemetry events for Project Browser](https://github.com/dbuytaert/drupal-digests/blob/main/issues/drupal-cms/3591488.md)


## Rector rules

[Rector](https://getrector.com) can rewrite PHP code automatically, so you don't have to update deprecated API calls by hand. These [209 Rector rules](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules), extracted from Drupal core issues using AI, handle recent deprecations and new coding patterns.

```bash
git clone --depth 1 https://github.com/dbuytaert/drupal-digests.git
composer require --dev rector/rector

# Rewrite deprecated code (dry run first)
vendor/bin/rector process web/modules/custom \
  --config drupal-digests/rector/all.php --dry-run
```

### Latest rules
_209 rules · 1 new this week_

- [Remove deprecated $error_type argument from doTrustedCallback() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-error-type-argument-from-3081025.php)
- [Remove deprecated no-op InstallerTestBase::setUpProfile() calls](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/remove-deprecated-no-op-installertestbase-setupprofile-calls-3520028.php)
- [Replace deprecated update.inc global functions with the DatabaseUpdate service](https://github.com/dbuytaert/drupal-digests/tree/main/rector/rules/replace-deprecated-update-inc-global-functions-with-the-3391683.php)


## RSS feeds

- [Drupal Core](https://dbuytaert.github.io/drupal-digests/feeds/drupal-core.xml)
- [Drupal CMS](https://dbuytaert.github.io/drupal-digests/feeds/drupal-cms.xml)
- [Drupal Canvas](https://dbuytaert.github.io/drupal-digests/feeds/drupal-canvas.xml)
- [Drupal AI](https://dbuytaert.github.io/drupal-digests/feeds/drupal-ai.xml)
- [Rector rules](https://dbuytaert.github.io/drupal-digests/feeds/rector.xml)

---

*AI generated and may contain errors. Created by [Dries Buytaert](https://dri.es/).*
